<?php
    

    // API IMAGE Project, JsWT Exam TPS, Google API [Spreadsheet]
    class response {

        private const CT_JSON = "Content-Type: application/json; charset=utf-8";
        private const CT_TEXT = "Content-Type: text/plain; charset=utf-8";
        private const CT_HTML = "Content-Type: text/html; charset=UTF-8";

        private const ID_SUCCESSFUL = 200;
        private const ID_SERVER_ERROR = 500;
        private const ID_CLIENT_ERROR = 400;

        private const HTTP_RESPONSE_STATUS_CODES = array(

            self::ID_SUCCESSFUL => array(
                
                200 => "OK",
                201 => "Created",
                204 => "No Content"
            ),

            self::ID_CLIENT_ERROR => array(

                400 => "Bad Request",
                401 => "Unauthorized",
                403 => "Forbidden",
                404 => "Not Found",
                405 => "Method Not Allowed",
                429 => "Too Many Requests"
            ),

            self::ID_SERVER_ERROR => array(

                500 => "Internal Server Error",
                501 => "Not Implemented"
            )
        );

        public static function client_error(int $status_code = 400, $status_msg = false, array $array = array()){

            if (!self::status_code_valid($status_code, self::ID_CLIENT_ERROR)) response::server_error(500);

            http_response_code($status_code);   
            $status_msg = self::get_status_msg(self::ID_CLIENT_ERROR, $status_code, $status_msg);

            $json = json_encode(array_merge(array('success' => false,'status_code' => $status_code,'status_message' => $status_msg), $array), JSON_PRETTY_PRINT);

            self::send($json, true);
        }

        public static function server_error(int $status_code = 500, $status_msg = false, array $array = array()){

            if (!self::status_code_valid($status_code, self::ID_SERVER_ERROR)) response::server_error(500);

            http_response_code($status_code);
            $status_msg = self::get_status_msg(self::ID_SERVER_ERROR, $status_code, $status_msg);

            $json = json_encode(array_merge(array('success' => false,'status_code' => $status_code,'status_message' => $status_msg), $array), JSON_PRETTY_PRINT);
            
            self::send($json, true);
        }

        public static function successful(int $status_code = 200, $status_msg = false, array $array = array()){

            if (!self::status_code_valid($status_code, self::ID_SUCCESSFUL)) response::server_error(500);

            http_response_code($status_code);
            $status_msg = self::get_status_msg(self::ID_SUCCESSFUL, $status_code, $status_msg);

            $json = json_encode(array_merge(array('success' => true,'status_code' => $status_code,'status_message' => $status_msg), $array), JSON_PRETTY_PRINT);

            self::send($json, false);
        }

        private static function ctype($option){

            switch (strtoupper($option)){
                case 'TEXT': {   
                    header(self::CT_TEXT);
                    break;
                }
                case 'JSON': default: {
                    header(self::CT_JSON);
                    break;
                }
                case 'HTML': {
                    header(self::CT_HTML);
                    break;
                }
            }
        }

        private static function status_code_valid(int $status_code, int $id){

            return ($status_code >= $id && $status_code <= $id + 99);
        }

        private static function get_status_msg(int $index, int $status_code, $status_msg){

            return ( 
                $status_msg !== false ? 
                    $status_msg : (@self::HTTP_RESPONSE_STATUS_CODES[$index][$status_code] === null ? 
                        "Status Message Not Available" : self::HTTP_RESPONSE_STATUS_CODES[$index][$status_code])
            );
        }

        private static function send($response, bool $exit){

            self::ctype('JSON');
            echo $response;

            if ($exit === true) exit;
        }

        public static function download_file($filename, bool $exit = true){
            header("Cache-Control: public");
            header("Content-Description: file transfer");
            header("Content-Disposition: attachment; filename={$filename}");
            header("Content-Type: application/zip");
            header("Content-Transer-Encoding: binary");
            http_response_code(200);
            readfile($filename);
            unlink($filename);
            if ($exit === true) exit;
        }
    }

    // Gestione dati 
    class sqlc {
        
        private static $conn = null;
        private static $stmt = null;

        const QRYS = [
            "UPDATE_LIKES" => "UPDATE `EDC_vals` SET c_like = ? WHERE id_img = ? ;",
            "GET_LIKES" => "SELECT c_like FROM `EDC_vals` WHERE id_img = ? ;",
            "GET_INFO_IMGS" => "SELECT id, `path`, `name`, info FROM `EDC_imgs` ORDER BY show_order ASC;",
            "GET_NAME&LIKE" => "SELECT i.name, v.c_like FROM EDC_imgs AS i, EDC_vals AS v WHERE i.id = v.id_img ORDER BY v.c_like DESC, i.name ASC;"
        ];

        public static function connect($address = "localhost", $name = "mywebs", $password = "", $dbname = "my_mywebs"){
            self::$conn = new mysqli($address, $name, $password, $dbname);
            if (self::$conn->connect_error) 
            {
                self::$conn = null;
                response::server_error(500, "Connection failed");
            }
        }

        public static function qry_exec(string $qry, string $type){
            sqlc::connect();
            if ($type === "SELECTION")
            {
                $res = self::$conn->query($qry)->fetch_all();
                return empty($res) ? -1 : $res;
            }
            else
            {
                $res = self::$conn->query($qry);
                return $res;
            }
        }

        private static function prep($qry)
        {
            self::$stmt = null;
            self::$stmt = self::$conn->prepare($qry);
        }

        public static function get_likes($img_id){
            self::prep(self::QRYS['GET_LIKES']);
            self::$stmt->bind_param("i", intval($img_id));
            self::$stmt->execute();
            $data = self::$stmt->get_result()->fetch_assoc();
            return $data;
        }

        public static function update_likes($img_id, $liked){
            
            self::connect();
            $likes = self::get_likes($img_id)['c_like'];

            if ($liked)
                $likes = intval($likes) + 1;
            else
                $likes = intval($likes) - 1;
            
            if ($likes < 0) $likes = 0;

            self::prep(self::QRYS['UPDATE_LIKES']);
            self::$stmt->bind_param("ii", $likes, $img_id);
            self::$stmt->execute();
        }

        public static function get_info_imgs(){
            self::connect();
            $data = self::qry_exec(self::QRYS['GET_INFO_IMGS'], "SELECTION");
            return $data;
        }

        public static function get_nameANDlike(){
            self::connect();
            $data = self::qry_exec(self::QRYS["GET_NAME&LIKE"], "SELECTION");
            return $data; // response[name] response[n_like];
        }
    }

?>