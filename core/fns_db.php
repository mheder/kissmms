<?php

############################################################################
#
# Copyright [2020] [Mihály Héder]
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#    http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
#
############################################################################


# 
# This will of course optimally be using msqli persistent connections
# which is effectively connection pooling
# This depends on your php config, see
# mysqli.allow_persistent
#
function getConnection() {
    $con = mysqli_connect($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $GLOBALS['db_name']);
    mysqli_set_charset($con, "utf8");
    if (!$con) {
        error_log("Error: " . mysqli_connect_error());
        exit();
    }
    return $con;
}

# 
# Optimally it releases the connection back to the pool
# This depends on your php config, see
# mysqli.allow_persistent
#
function closeConnection($con) {
    mysqli_close($con);
}

function exec_fetch_scalar($stmt) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $content);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $content;
}

function exec_fetch_vector($stmt) {
    $ret = array();
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $content);
    while (mysqli_stmt_fetch($stmt)) {
        array_push($ret,$content);
    }
    mysqli_stmt_close($stmt);
    return $ret;
}

function exec_fetch_matrix($stmt) {
    $ret = array();
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($ret,$row);
    }
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    return $ret;
}

function exec_insert($stmt,$con) {
    mysqli_stmt_execute($stmt);
    $ret = mysqli_insert_id($con);
    mysqli_stmt_close($stmt);
    return $ret;
}

function exec_update($stmt,$con) {
    $ret = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ret;
}

function exec_generic($qmethod, $sql, $param1 = null, $param2 = null, $param3 = null, $param4= null, $param5 = null) {
    $con = getConnection();
    auxi_log_trace("Running: $sql, $param1, $param2, $param3, $param4, $param5");
    if ($stmt = mysqli_prepare($con, $sql)) {
        if (isset($param1) and !isset($param2)) {
            mysqli_stmt_bind_param($stmt, "s", $param1);
        }
        if (isset($param1) and isset($param2) and !isset($param3)) {
            mysqli_stmt_bind_param($stmt, "ss", $param1, $param2);
        }
        if (isset($param1) and isset($param2) and isset($param3) and !isset($param4)) {
            mysqli_stmt_bind_param($stmt, "sss", $param1, $param2, $param3);
        }
        if (isset($param1) and isset($param2) and isset($param3) and isset($param4) and !isset($param5)) {
            mysqli_stmt_bind_param($stmt, "ssss", $param1, $param2, $param3, $param4);
        }
        if (isset($param1) and isset($param2) and isset($param3) and isset($param4) and isset($param5)) {
            mysqli_stmt_bind_param($stmt, "sssss", $param1, $param2, $param3, $param4, $param5);
        }
        switch($qmethod) {
            case "scalar":
                $ret = exec_fetch_scalar($stmt);
                break;
            case "vector":
                $ret = exec_fetch_vector($stmt);
                break;
            case "matrix":
                $ret = exec_fetch_matrix($stmt);
                break;
            case "insert": 
                $ret = exec_insert($stmt,$con);
                break;
            case "update": 
                $ret = exec_update($stmt,$con);
                break;
            }
    } else {
        auxi_log_error("error in exec_generic:" . mysqli_error($con) . " Attempted SQL:". $sql);
        $ret = null;
    }
    closeConnection($con);
    return $ret;
}


function query_scalar($sql, $param1 = null, $param2 = null, $param3 = null, $param4= null, $param5 = null) {
    return exec_generic("scalar", $sql, $param1, $param2, $param3, $param4, $param5);
}

function query_vector($sql, $param1 = null, $param2 = null, $param3 = null, $param4= null, $param5 = null) {
    return exec_generic("vector", $sql, $param1, $param2, $param3, $param4, $param5);
}

function query_matrix($sql, $param1 = null, $param2 = null, $param3 = null, $param4= null, $param5 = null) {
    return exec_generic("matrix", $sql, $param1, $param2, $param3, $param4, $param5);

}

function db_insert($sql, $param1 = null, $param2 = null, $param3 = null, $param4= null, $param5 = null) {
    return exec_generic("insert", $sql, $param1, $param2, $param3, $param4, $param5);
}

function db_update($sql, $param1 = null, $param2 = null, $param3 = null, $param4= null, $param5 = null) {
    return exec_generic("update", $sql, $param1, $param2, $param3, $param4, $param5);
}

?>