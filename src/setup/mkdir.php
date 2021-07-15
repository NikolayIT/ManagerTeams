<?php
for ($i = 0; $i <= 9; $i++)
{
   mkdir("./cache/matches/{$i}/");
   for ($j = 0; $j <= 9; $j++)
   {
      mkdir("./cache/matches/{$i}/{$j}/");
      for ($k = 0; $k <= 9; $k++)
      {
         mkdir("./cache/matches/{$i}/{$j}/{$k}/");
         for ($l = 0; $l <= 9; $l++) mkdir("./cache/matches/{$i}/{$j}/{$k}/{$l}/");
      }
   }
}
?>