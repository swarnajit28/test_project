<!-- <script src="{{ asset('public/js/jQuery_v3.3.1.js') }}" ></script>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script> -->
<script src="{{ asset('public/js/jquery-2.2.4.min.js') }}" ></script>
 <script type="text/javascript">
     $(document).ready(function() {
           var d = new Date();
           var n="© "
           n+=d.getFullYear();
           document.getElementById("year").innerHTML = n;
        });
    </script>