//   js\job\list.js

//import "../app"
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';
$(document).ready(function () {
    //hide content of categoriesContent
    $("div.categoriesContent").not(":first").hide();
    $(".categoriesTitle").click(function () {
        $(".categoriesContent").slideUp();
        if($(this).find("i").hasClass("fa-chevron-up")){
            $(this).find("i").removeClass("fa-chevron-up");
            $(this).find("i").addClass("fa-chevron-down");
        }
        else {
            $(this).find("i").removeClass("fa-chevron-down");
            $(this).find("i").addClass("fa-chevron-up");
            $(this).parent("div").find(".categoriesContent").slideDown();
        }
    });
    $(".nextPage, .previousPage").click(function (e) {
        e.preventDefault();
        let path =$(this).closest("ul").find(".currentPage").data('path');
        let page=$(this).closest("ul").find(".currentPage").html();
        let pagePos= $(this).hasClass("nextPage") ? parseInt(page)+1 : parseInt(page)-1;
        $(this).closest("ul").find(".currentPage").html(pagePos);
        let categoryname=$(this).closest("ul").find(".currentPage").data('categoryname');
        $("#categoryselect").data("categoryselect",categoryname);
        $.ajax({
            url: path,
            type: "GET",
            dataType: "html",
            data: {
                "page":pagePos,
            },
            async: true,
            success: function (data) {

                let categoryselect=$("#categoryselect").data("categoryselect");
                let content=$("."+categoryselect+"").find(".categoriesContent");
                /*let page= $("."+categoryselect+"").find(".currentPage");
                page.html(parseInt(page.html())+1);*/
                content.empty().html(data);
                /*let htmlData;
                $.each(data.jobs,function( index ,job ) {
                    console.log( index + ": " + job.type);
                    htmlData+='<div class="job row">'
                        +'<div class="col-md-7">'
                        + '<a href="#">'
                        +'<img height="200px" width="200px" class="img-thumbnail"   src="images/ '+job.logo+') }}" alt="'+job.company +'">'
                        +'</a>'
                        +'</div>'
                        +'<div class="col-md-5">'
                        +'<h3>'+ job.position +'</h3>'
                        +'<p>'+job.description +'</p>'
                        +'<p>Posted on  '+job.createdAt|date("m/d/Y") +'</p>'
                        +'<a class="btn btn-primary" href="'+Routing.generate('job.show', {'id':  job.id });+'">See moreeeee</a>'
                        +'</div>'
                    +'</div>'
                    +'<hr>'
                });
                alert(htmlData);
                content.remove().html(htmlData);*/


                /*$('#textAjax').text(data.info);
                $('#myModal').modal('show');*/
            }
        });
        console.log(page)
    });
});