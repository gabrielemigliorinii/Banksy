<?php

    require_once 'api.php';

    header('Access-Control-Allow-Methods: GET');

    if (isset($_SERVER['REQUEST_METHOD'])){

        if (isset($_GET['poster_links']) && count($_GET) === 1){
            $links = sqlc::get_info_imgs();
            response::successful(200, false, array("links" => $links));
            exit;
        }

        if (isset($_GET['show_classification']) && count($_GET) === 1){
            $data = sqlc::get_nameANDlike();
            response::successful(200, false, array("classification" => $data));
            exit;
        }

        if (isset($_GET['action']) && isset($_GET['id']) && count($_GET) === 2){
            session_start();
            $id = $_GET['id'];

            switch ($_GET['action']){

                case 'LIKE': {

                    if ($_SESSION["im__".$id] === "UNLIKED" || !isset($_SESSION["im__".$id])){
                        sqlc::update_likes($id, true);
                        $_SESSION["im__".$id] = "LIKED";
                        response::successful(200, "{$id} liked");
                        exit;
                    }

                    break;
                }

                case 'UNLIKE': {

                    if ($_SESSION["im__".$id] === "LIKED"){
                        sqlc::update_likes($id, false);
                        $_SESSION["im__".$id] = "UNLIKED";
                        response::successful(200, "{$id} unliked");
                        exit;
                    }
                    break;
                }

                default: {
                    response::client_error(400, "BAD REQUEST");
                    break;
                }
            }
        }

        response::client_error(400, "BAD REQUEST");
    }
    else response::server_error();

    
?>