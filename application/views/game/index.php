<?=View::factory('includes/header')->render()?>

   <script>
      var target = '/game/play';
      $(document).ready(setFormHandler(target));
      
      var time = 1000;
      $(document).ready(setJoinCheck(target, time));
   </script>

  <p>Olá, <?=$user->name?>!</p>
  
  <p>Possíveis adversários no momento:</p>
  <form id="create" method="POST" action="/game/create">
    <p>
      <? foreach ($logged as $usr) : ?>
         <input type="radio" name="opponent" value="<?=$usr->id?>"/><?=$usr->name?></br>
      <? endforeach; ?>   
    </p>
    <input type="submit" value="Jogar"/>
  </form>

<?=View::factory('includes/footer')->render()?>
