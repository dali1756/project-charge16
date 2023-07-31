    <!-- footer -->
    <footer id="footer" class="sticky-footer footer-bg">
        <div class="copyright">
            <?php print "&copy; AOTECH合創數位科技2020"//.$lang->line("index.browser_suggested_size").": 1280 * 900為佳<br> "; ?>
        </div>
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(function() {
            function footerPosition() {
                var contentHeight = document.body.scrollHeight;//網頁正文全文高度
                var winHeight = window.innerHeight;//可視窗口高度，不包括瀏覽器頂部工具欄
                if (!(contentHeight > winHeight)) {
                    //當網頁正文高度小於可視窗口高度時，為footer添加類fixed-bottom
                    $('#footer').css({'position' : 'fixed'});
                } else {
                    $('#footer').css({'position' : 'static'});
                }
            }
            footerPosition();
            $(window).resize(footerPosition);
        });
        $(function(){
            $('.scroll-to-top').click(function(){ 
                $('html,body').animate({scrollTop:0}, 333);
            });
            $(window).scroll(function() {
                if ( $(this).scrollTop() > 100 ){
                    $('.scroll-to-top').fadeIn(222);
                } else {
                    $('.scroll-to-top').stop().fadeOut(222);
                }
            }).scroll();
        });
    </script>

</body>
</html>