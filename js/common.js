document.querySelector("#user-header").addEventListener("click", (e) => {
    document.querySelector("#user-header").classList.toggle("open")
})

document.querySelector(".edit").addEventListener("click", (e) => {
    document.querySelector("#user_edit").classList.toggle("open")
})


$(document).ready(() => {
    $("#edit_profile").click(function (e) {
        e.preventDefault();
        if ($(".about-text textarea").length == 0) {
            var about = $(".about-text");
            var text = about.html();
            var text_2 = text.replace(/<\/?[a-zA-Z]+>/gi, " ");
            about.html('<textarea>' + text_2 + '</textarea> <div class="btns"><button class="btn btn-success apply">Применить</button><button class="btn btn-danger cancel">Отмена</button></div>');
            $("#user_edit").removeClass("open");


            $(".apply").click(() => {
                applyAbout();
            })

            $(".cancel").click(() => {
                returnAboutBlock(text)
            })


            function applyAbout() {
                console.log("about")
                var text = $(".about-text textarea").val();
                text = text.replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/\n/g, '</p><p>')
                $.ajax({
                    url: "/?r=user/change",
                    type: "POST",
                    data: {
                        user: {
                            about: text,
                        }
                    },
                    success: (html) => {
                        console.log(html)
                        returnAboutBlock(text);
                    }
                })
            }
            function returnAboutBlock(text) {
                $(".about-text").html(text);
            }
        }
    });

    $(".load-photo input[type='file']").change(() => {
        $(".load-photo form").submit();
    })

})