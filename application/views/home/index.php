<?=View::factory('includes/header')->render()?>

   <script>
      var target = '/game';
      $(document).ready(setFormHandler(target));
   </script>

  <form id="create" method="POST" action="/start">
    <input type="text" name="name" value="Seu nome"/>
    <input type="submit" value="Entrar"/>
  </form>

<?=View::factory('includes/footer')->render()?>
