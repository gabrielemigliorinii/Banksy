
"use strict"

let page_i = 0;
let focus_i = 0;
let polling = undefined;

class Polling {
    
    constructor (action, ms) {
        this.timer = undefined;
        this.action = action;
        this.ms = ms;
    }

    Start = () => this.timer = setInterval(this.action, this.ms);
    Stop = () => clearInterval(this.timer);
}


class Events {

    static onclick_changeLocation = (link) => {
        window.location.assign(link);
    }

    static #move_to = (__top) => {
        window.scrollTo({top:__top, behavior:'smooth'});
    }

    static onclick_changePage(ids) {
        
        if (page_i === ids) { this.#move_to(0); return;};
        const idh = page_i;

        $("#ID_IMGS").empty();

        if (ids === 1){
            PageFunc.showClassification();
            polling = new Polling (PageFunc.showClassification, 1500);
            polling.Start();
        }else{
            if (polling !== undefined) polling.Stop();
        }

        $('#ID_SB0').val('');

        CSS.hide("#DIV_P"+idh);
        CSS.show("#DIV_P"+ids);

        CSS.nofocus("#IDn"+focus_i);
        CSS.focus("#IDn"+ids);

        page_i = ids;   // indice pagina corrente aggiornato
        focus_i = ids;
    }

}


class PageFunc {

    static async showClassification(){
        $.ajax({
            type: 'GET',
            url: 'src/php/server.php?show_classification=true',
            success: (response) => {
                document.getElementById("ID_CLASS").innerHTML = "";
                var h = "";
                h += "<table>";
                h += "<th>TITOLO</th><th>Likes</th>";
                for (let i=0; i<response.classification.length; i++){
                    h += "<tr>";
                    h += "<td>"+response.classification[i][0]+"</td><td>"+response.classification[i][1]+"</td>";
                    h += "</tr>";
                }
                h += "</table>";
                document.getElementById("ID_CLASS").innerHTML += h;

                console.log(response);
            },
            error: (xhr) => {
                window.location.href = "home";
            }
        })
    }
    
    static async showPosters(links, id_list_img="ID_IMGS_0"){

        for (let i=0; i<links.length; i++){   

            var like = "";
            like += "<div class='stage'>";
            like += "<div class='heart'></div>";
            like += "</div>";

            var h = "";
            h += "<div class='image'>"
            h += "  <img class='image__img' src='"+links[i][1]+"' id='"+links[i][0]+"' alt='Title'>"
            h += "  <div class='image__overlay image__overlay--blur'>";
            h += "      <div class='image__title'>"+links[i][2]+"</div>";
            h += "      <p class='image__description'>"+links[i][3]+"</p>";
            h += "     <input type='checkbox' class='image__like' id='LIKE_"+links[i][0]+"'>";
            h += "  </div>";
            h += "</div>";

            document.getElementById(id_list_img).innerHTML += h;
        }
    }
}

class CSS {

    static hide(id){
        $(id).removeClass("visible");
        $(id).addClass("hidden");
    }

    static show(id){
        $(id).removeClass("hidden");
        $(id).addClass("visible");
    }

    static focus(id){
        $(id).css({"font-size":"1.5rem"});
    }

    static nofocus(id){
        $(id).css({"font-size":"1rem"});
    }
}





