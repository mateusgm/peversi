{
   "state": {
      "turn": "<?=$game->state->turn?>",
      "idle": "<?=$game->state->idle?>", 
      "status": "<?=$game->state->status?>",
      "black": <?=$game->state->black?>,
      "white": <?=$game->state->white?>
      },
   "game": {
      "id": <?=$game->id?>,
      "turn": <?=$game->turn?>,
      "idle": <?=$game->idle?>     
      },
   "board": {
      <? foreach ($game->state->board as $y => $row) : 
            foreach ($row as $x => $stone) :
               echo "\"${y}${x}\": \"$stone\",";
            endforeach; 
         endforeach; ?>
      "nenhum": ""
      },
   "available": [
      <? foreach ($game->state->available as $pos) : 
            echo "\"$pos\",";
         endforeach; ?>
      "0"
   ]
}
