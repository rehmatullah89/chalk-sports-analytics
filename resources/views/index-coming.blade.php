<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chalk Sports Analytics</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


	<link rel="stylesheet" href="{{ asset('js/scrollbar') }}/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="{{ asset('css') }}/all.css">
    <link rel="stylesheet" href="{{ asset('css') }}/coming-soon.css">
  </head>
  <body>
    <div id="cs-wrapper">
        <div class="banner-holder">
            <div class="logo">
                <a href="#">
                    Chalk Sports Analytics
                </a>
                <a href="#">
                    NFL
                </a>
            </div>
            <h2>We are coming soon!!</h2>
            <p>Stay tuned for something amazing</p>
            <div class="subscribe">
                <p>Subscribe to our mailing list to get latest updates</p>
                    {{--<input type="email" class="form-control" placeholder="Enter your email">
                    <span class="input-group-btn">
                        <button class="btn" type="submit">Subscribe</button>
                    </span> --}}
                    <!--start mc_embed_signup-->
                    <div id="mc_embed_signup">
                        <form action="https://chalksportsanalytics.us14.list-manage.com/subscribe/post?u=6c243e7c7fe9c4b00b3b4aabe&amp;id=f1120bbec6&amp;f_id=00898ae0f0" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                            <div id="mc_embed_signup_scroll" class="input-group">
                                <div class="mc-field-group">
                                    <input type="email" value="" name="EMAIL" class="form-control required email" id="mce-EMAIL" required>
                                    <span id="mce-EMAIL-HELPERTEXT" class="helper_text"></span>
                                </div>
                                <div id="mce-responses" class="clear foot">
                                    <div class="response" id="mce-error-response" style="display:none"></div>
                                    <div class="response" id="mce-success-response" style="display:none"></div>
                                </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_6c243e7c7fe9c4b00b3b4aabe_f1120bbec6" tabindex="-1" value=""></div>
                                <div class="optionalParent input-group-btn">
                                    <div class="clear foot">
                                        <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn button">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='ADDRESS';ftypes[3]='address';fnames[4]='PHONE';ftypes[4]='phone';fnames[5]='BIRTHDAY';ftypes[5]='birthday';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
                    <!--End mc_embed_signup-->
            </div>
            <ul class="socials-icon">
                <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                <li><a href="#"><i class="fa-brands fa-linkedin"></i></a></li>
            </ul>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script src="{!! asset('js/scrollbar/jquery.mCustomScrollbar.concat.min.js') !!}"></script>
<script>
//     $(window).on('load', function () {
//         $("#header").removeClass("sticky-header")
//   });
    $(window).scroll(function () {
     var sc = $(window).scrollTop()
    if (sc > 1) {
        $("#header").addClass("sticky-header")
    } else {
        $("#header").removeClass("sticky-header")
    }
});

    $(document).ready(function(){
        $(".burger2").click(function(){
            $(".burger2").toggleClass("open");
            $("#nav").toggleClass("slideMenu");
        })
    })

    var firstTabEl = document.querySelector('#myTab ul li:last-child a')
    var firstTab = new bootstrap.Tab(firstTabEl)
    firstTab.show();



</script>
  </body>
</html>
