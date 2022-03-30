<?php

    require_once '../php/api.php';

    if (isset($_SERVER['REQUEST_METHOD'])){
        switch ($_SERVER['REQUEST_METHOD']){
            case 'GET': {
                session_start();
                break;
            }

            default: {
                response::client_error(405);
                break;
            }
        }
    }
    else response::server_error(500);

?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <script src="src/front_end/script.js"></script>
    <title> Bansky Gallery </title>
    <link rel="stylesheet" href="src/front_end/style.css">
</head>
<body>

    <!--  Menu in alto [SEMPRE VISIBILE]  -->
    <nav>
        <ul>
            <li><a id="IDn0" class="navhome" onclick="Events.onclick_changePage(0)">Home</a></li>
            <li><a id="IDn1" onclick="Events.onclick_changePage(1)">Classifica</a></li>
            <li><a id="IDn2" onclick="Events.onclick_changePage(2)">About</a></li>
        </ul>
    </nav>

    <!--  Pagina principale  -->

    <div class="visible" id="DIV_P0">
        
        <div class='textDiv'>
            <div class="tls"><p id="ID_RS_1">Bansky</p></div>
            <p class="par">
                Banksy è un artista e writer inglese, considerato uno dei maggiori esponenti della street art, la cui vera identità rimane ancora sconosciuta.
                Le sue opere sono spesso a sfondo satirico e riguardano argomenti come la politica, la cultura e l'etica.
                Sono noti anche i suoi impegni come attivista, politico e regista.
                <br><br><br>La street art di Banksy è di natura satirica e sovversiva. Le sue opere combinano un umorismo oscuro con graffiti eseguiti con la 
                tecnica dello stencil. I suoi murales di critica sociale e politica sono apparsi su strade, muri e ponti di città in 
                tutto il mondo. Il lavoro di Banksy è nato nella scena culturale underground di Bristol, che ha visto collaborare artisti e 
                musicisti
            </p>
        </div>
        
        <div id="ID_IMGS_0">
            <div class="tls"><p id="ID_RS_0">Opere d'arte</p></div>
            <p class="par">
                Banksy è considerato uno dei maggiori esponenti di una branca della street art molto famosa, nota come post-graffiti e 
                guerrilla art. L'arte di Banksy, trova espressione nella dimensione stradale e pubblica dello spazio urbano, 
                realizzando pezzi che documentano la povertà della condizione umana. <br><br>
            </p>
            <div class="tls"><p id="ID_RS_0" style="font-size:3rem">Tematiche</p></div>
            <p class="par">
                Le sue opere con un taglio ironico e satirico trattano 
                tematiche tra quali: le assurdità della società occidentale, la manipolazione mediatica, l'omologazione, le atrocità della 
                guerra, l'inquinamento, lo sfruttamento minorile, la brutalità della repressione poliziesca e il maltrattamento degli animali. 
                Per veicolare questo messaggio viene fatto ricorso a un'ampia gamma di soggetti, quali scimmie 
                poliziotti, membri della famiglia reale.
                <br>
                Di seguito sono riportate alcune delle sue opere d'arte piu' famose.
            </p>
        </div>
    
    </div>


    <div class="hidden" id="DIV_P1">
        <div class="tls"><p id="ID_RS_1" style="line-height:40px;font-size:4rem;text-transform:none;">Classifica Opere</p></div>
        <table id="ID_CLASS" class="tabClass"></table>
    </div>


    <!-- About -->
    <div class="hidden" id="DIV_P2">
        <div id="MY_INFO_CONTAINER">
            <p>Gabriele Migliorini</p>
            <p>5ID</p>
        </div>
    </div>

    <footer id="footer1">
        <p id="footerText">Bansky Gallery</p>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>
</html>

<script>
    
    $(document).ready(() => {
        getImages();
    });

    const showImages = (links) => {
        PageFunc.showPosters(links);
    }

    const getImages = () => {

        const options = {
            url: 'src/php/server.php?poster_links=true',
            type: 'GET',
            success: (response) => {
                console.log(response.links);
                showImages(response.links);   // show images
                syncImages(response.links); // sync session data (likes)
            },
            error: (xhr) => {
                console.log(xhr);
            }
        };

        $.ajax(options);
    }

    const syncImages = (obj) => {
        const s = JSON.parse('<?php echo json_encode($_SESSION); ?>');
        console.log(s);
        for (let i=0; i<obj.length; i++){
            
            if (s['im__'+obj[i][0]] !== undefined && s['im__'+obj[i][0]] !== 'UNLIKED'){
                $("#LIKE_" + obj[i][0]).prop('checked', "1");
            }

            console.log(document.getElementById("LIKE_" + obj[i][0]));

            $("#LIKE_" + obj[i][0]).on('change', (elem) => {

                elem.target.id = elem.target.id.replace("LIKE_", "");
                const data = {id: elem.target.id, action: elem.target.checked ? "LIKE" : "UNLIKE"};
                console.log(data);

                $.ajax({
                    url: 'src/php/server.php',
                    data: data,
                    type: 'GET',
                    success: (response) => {
                    console.log(response);
                    },
                    error: (xhr) => {
                    console.log(xhr);
                    }
                })
            });
        }
    }

</script>
