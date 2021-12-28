</div>
</div>

<!-- JQuery -->
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<!-- Slick.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- My JS -->
<script type="text/javascript" src="js/scripts.js"></script>

<!-- Slick Settings -->
<?php
    switch(basename($_SERVER['PHP_SELF'])){
        case "scores.php": ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    $('.slider-for').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false,
                        fade: false,
                        speed: 0,
                        asNavFor: '.slider-nav',
                        swipe: false,
                        draggable: false,
                        infinite: false,
                        speed: 500,
                        swipe: true,
                        centerMode: true,
                        centerPadding: '0%',
                    });
                    $('.slider-nav').slick({
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        asNavFor: '.slider-for',
                        dots: false,
                        arrows: false,
                        centerMode: true,
                        centerPadding: '5%',
                        focusOnSelect: true,
                        draggable: true,
                        mobileFirst: true,
                        infinite: false,
                        speed: 500,
                        swipe: true,
                    });
                });
            </script>
            <?php
            break;
        case "vote.php": ?>
            <script type="text/javascript">
                function checkScore(candidateId){
                    // Get the current total voted points
                    var totalVoted = 0;
                    <?php foreach($candidates as $candidate): ?>
                    totalVoted += parseInt($('#slider<?=$candidate['Id']?>').val());
                    <?php endforeach; ?>

                    // Check for max available points
                    if(totalVoted > <?=$account["Score"]?>){
                    // Get the current selected value
                    let selectedValue = $('#slider' + candidateId).val();
                    // Calculate the new value correctly with set limit
                    let newSelectedValue = selectedValue - (totalVoted - <?=$account["Score"]?>);
                    // Change actual values
                    $('#slider' + candidateId).val(newSelectedValue);
                    // Change display values
                    $(".pointsCandidate" + candidateId).text(newSelectedValue);
                    $(".userPointsLeft").text(0);
                    }else{
                    // Change display values
                    $(".userPointsLeft").text(<?=$account["Score"]?> - totalVoted);
                    }
                    // Change display values
                    $(".pointsCandidate" + candidateId).text($('#slider' + candidateId).val());
                };
                $(document).ready(function(){
                    // Slick settings for voting system
                    $('.slider-for').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false,
                        fade: false,
                        speed: 0,
                        asNavFor: '.slider-nav',
                        swipe: false,
                        draggable: false,
                        infinite: false,
                    });
                    // Slick settings for candidate list
                    $('.slider-nav').slick({
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        asNavFor: '.slider-for',
                        dots: false,
                        arrows: false,
                        centerMode: true,
                        centerPadding: '5%',
                        focusOnSelect: true,
                        draggable: true,
                        mobileFirst: true,
                        infinite: false
                    });
                });
            </script>
            <?php 
            break;
        default:
            break;
    }
?>

</body>
</html>