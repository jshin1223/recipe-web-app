</div> <!-- End of container -->

<footer style="text-align: center; margin-top: 2rem;">
    <!-- Copyright -->
    <p>&copy; <?php echo date("Y"); ?> Recipe Web App. All rights reserved.</p>

    <!-- Translation and Font Size Controls -->
    <div style="display: flex; justify-content: center; align-items: center; margin-top: 10px; gap: 10px;">
        <!-- Google Translate Widget -->
        <div id="google_translate_element"></div>

        <!-- Font Size Adjustment Buttons -->
        <div style="display: flex; gap: 5px;">
            <button class="btn btn-outline-secondary btn-sm font-size-btn" data-font="small" aria-label="Small Font Size">A-</button>
            <button class="btn btn-outline-secondary btn-sm font-size-btn" data-font="medium" aria-label="Default Font Size">A</button>
            <button class="btn btn-outline-secondary btn-sm font-size-btn" data-font="large" aria-label="Large Font Size">A+</button>
        </div>
    </div>

    <!-- Google Translate integration script -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            if (!document.querySelector('#google_translate_element .goog-te-gadget')) {
                new google.translate.TranslateElement({
                    pageLanguage: 'en',
                    layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                    autoDisplay: false
                }, 'google_translate_element');
            }
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</footer>

<!-- Bootstrap and jQuery Scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>

</body>
</html>
