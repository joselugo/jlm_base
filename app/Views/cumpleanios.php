 <!-- Main content -->
 <section class="container" style="margin-top: 20px;">
   <!-- Small boxes (Stat box) -->
   <div class="jumbotron mx-center" style="padding:20px">
     <h1>Feliz cumpleaños atrasado</h1>
     <p class="lead">Felicidades <?= $cumpleanios; ?></p>
   </div>

   <!-- /.row -->
 </section>
 <script>
confetti.start(10000);
setInterval(function() {
  confetti.stop();
}, 25000);
 </script>
