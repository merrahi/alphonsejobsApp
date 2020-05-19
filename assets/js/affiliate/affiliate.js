//   js\affiliate\affiliate.js

//import "app"
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';
$(document).ready(function () {
    $(".activeState").click(function (e) {
        e.preventDefault();
        let path =$(this).data('path');
        $.ajax({
            url: path,
            type: "GET",
            dataType: "json",
            async: true,
            success: function (data) {

                let id=data.id;
                let isActive=data.isActive;
                //$("#activeState"+id).addClass("disabledButton");
                    isActive ? alert("ko") :  alert("koo");
                    isActive ? $("#activeState"+id).addClass("disabledButton").prop("disabled", true) : $("#activeState"+id).prop(disabled, false).removeClass("disabledButton");
                /*let page= $("."+categoryselect+"").find(".currentPage");
                page.html(parseInt(page.html())+1);*/
            }
        });
    });

});