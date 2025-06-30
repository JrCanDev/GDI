<?php
include "$root/inc/head.php";
?>

<div class="margin w3-border w3-padding" style="background: white;">
  <h3 class="w3-margin-top"><b>Semestre 1</b></h3>
  <table class='w3-table w3-bordered w3-border w3-margin-bottom'>
    <thead class='w3-light-gray'>
      <tr>
        <th>Groupes</th>
        <th>Nombre d'heures</th>
        <th>Nombre de semaines (pour 30h/semaine)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="1">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD1">
          <input type='hidden' name='typeTP' value="TPA">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPA</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '1', '', 'CM', 'TD1', 'TPA') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="1">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD1">
          <input type='hidden' name='typeTP' value="TPB">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPB</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '1', '', 'CM', 'TD1', 'TPB') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="1">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD2">
          <input type='hidden' name='typeTP' value="TPC">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPC</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '1', '', 'CM', 'TD2', 'TPC') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="1">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD2">
          <input type='hidden' name='typeTP' value="TPD">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPD</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '1', '', 'CM', 'TD2', 'TPD') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="1">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD3">
          <input type='hidden' name='typeTP' value="TPE">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPE</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '1', '', 'CM', 'TD3', 'TPE') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
    </tbody>
  </table>

  <div class="w3-border w3-margin-top w3-margin-bottom"></div>
  <h3 class="w3-margin-top"><b>Semestre 2</b></h3>
  <table class='w3-table w3-bordered w3-border w3-margin-bottom'>
    <thead class='w3-light-gray'>
      <tr>
        <th>Groupes</th>
        <th>Nombre d'heures</th>
        <th>Nombre de semaines (pour 30h/semaine)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="2">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD1">
          <input type='hidden' name='typeTP' value="TPA">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPA</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '2', '', 'CM', 'TD1', 'TPA') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="2">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD1">
          <input type='hidden' name='typeTP' value="TPB">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPB</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '2', '', 'CM', 'TD1', 'TPB') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="2">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD2">
          <input type='hidden' name='typeTP' value="TPC">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPC</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '2', '', 'CM', 'TD2', 'TPC') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="2">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD2">
          <input type='hidden' name='typeTP' value="TPD">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPD</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '2', '', 'CM', 'TD2', 'TPD') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="2">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD3">
          <input type='hidden' name='typeTP' value="TPE">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPE</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '2', '', 'CM', 'TD3', 'TPE') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
    </tbody>
  </table>

  <div class="w3-border w3-margin-top w3-margin-bottom"></div>
  <h3 class="w3-margin-top"><b>Semestre 3 FI</b></h3>
  <table class='w3-table w3-bordered w3-border w3-margin-bottom'>
    <thead class='w3-light-gray'>
      <tr>
        <th>Groupes</th>
        <th>Nombre d'heures</th>
        <th>Nombre de semaines (pour 30h/semaine)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="3">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD1">
          <input type='hidden' name='typeTP' value="TPA">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPA</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '3', '', 'CM', 'TD1', 'TPA') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="3">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD1">
          <input type='hidden' name='typeTP' value="TPB">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPB</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '3', '', 'CM', 'TD1', 'TPB') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="3">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD2">
          <input type='hidden' name='typeTP' value="TPC">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPC</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '3', '', 'CM', 'TD2', 'TPC') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="3">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD2">
          <input type='hidden' name='typeTP' value="TPD">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPD</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '3', '', 'CM', 'TD2', 'TPD') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
    </tbody>
  </table>

  <div class="w3-border w3-margin-top w3-margin-bottom"></div>
  <h3 class="w3-margin-top"><b>Semestre 3 APP</b></h3>
  <table class='w3-table w3-bordered w3-border w3-margin-bottom'>
    <thead class='w3-light-gray'>
      <tr>
        <th>Groupes</th>
        <th>Nombre d'heures</th>
        <th>Nombre de semaines (pour 30h/semaine)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="3">
          <input type='hidden' name='app' value="B">
          <input type='hidden' name='typeCM' value="CM-APP">
          <input type='hidden' name='typeTD' value="TD3">
          <input type='hidden' name='typeTP' value="">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TD3</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '3', 'B', 'CM-APP', 'TD3') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
    </tbody>
  </table>

  <div class="w3-border w3-margin-top w3-margin-bottom"></div>
  <h3 class="w3-margin-top"><b>Semestre 4 FI</b></h3>
  <table class='w3-table w3-bordered w3-border w3-margin-bottom'>
    <thead class='w3-light-gray'>
      <tr>
        <th>Groupes</th>
        <th>Nombre d'heures</th>
        <th>Nombre de semaines (pour 30h/semaine)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="4">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD1">
          <input type='hidden' name='typeTP' value="TPA">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPA</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '4', '', 'CM', 'TD1', 'TPA') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="4">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD1">
          <input type='hidden' name='typeTP' value="TPB">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPB</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '4', '', 'CM', 'TD1', 'TPB') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="4">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD2">
          <input type='hidden' name='typeTP' value="TPC">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPC</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '4', '', 'CM', 'TD2', 'TPC') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="4">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD2">
          <input type='hidden' name='typeTP' value="TPD">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPD</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '4', '', 'CM', 'TD2', 'TPD') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
    </tbody>
  </table>

  <div class="w3-border w3-margin-top w3-margin-bottom"></div>
  <h3 class="w3-margin-top"><b>Semestre 4 APP</b></h3>
  <table class='w3-table w3-bordered w3-border w3-margin-bottom'>
    <thead class='w3-light-gray'>
      <tr>
        <th>Groupes</th>
        <th>Nombre d'heures</th>
        <th>Nombre de semaines (pour 30h/semaine)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="4">
          <input type='hidden' name='app' value="A">
          <input type='hidden' name='typeCM' value="CM-APP">
          <input type='hidden' name='typeTD' value="TD3">
          <input type='hidden' name='typeTP' value="">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TD3 - parcours A</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '4', 'A', 'CM-APP', 'TD3') ?></td>
        <td class='w3-border'>au moins <?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="4">
          <input type='hidden' name='app' value="B">
          <input type='hidden' name='typeCM' value="CM-APP">
          <input type='hidden' name='typeTD' value="TD3">
          <input type='hidden' name='typeTP' value="">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TD3 - parcours B</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '4', 'B', 'CM-APP', 'TD3') ?></td>
        <td class='w3-border'>au moins <?= toFloat($week) ?> semaines</td>
      </tr>
    </tbody>
  </table>

  <div class="w3-border w3-margin-top w3-margin-bottom"></div>
  <h3 class="w3-margin-top"><b>Semestre 5 FI</b></h3>
  <table class='w3-table w3-bordered w3-border w3-margin-bottom'>
    <thead class='w3-light-gray'>
      <tr>
        <th>Groupes</th>
        <th>Nombre d'heures</th>
        <th>Nombre de semaines (pour 30h/semaine)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="5">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD1">
          <input type='hidden' name='typeTP' value="TPA">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPA - parcours A</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '5', '', 'CM', 'TD1', 'TPA') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="5">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD1">
          <input type='hidden' name='typeTP' value="TPB">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPB - parcours A</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '5', '', 'CM', 'TD1', 'TPB') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
    </tbody>
  </table>

  <div class="w3-border w3-margin-top w3-margin-bottom"></div>
  <h3 class="w3-margin-top"><b>Semestre 5 APP</b></h3>
  <table class='w3-table w3-bordered w3-border w3-margin-bottom'>
    <thead class='w3-light-gray'>
      <tr>
        <th>Groupes</th>
        <th>Nombre d'heures</th>
        <th>Nombre de semaines (pour 30h/semaine)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="5">
          <input type='hidden' name='app' value="A">
          <input type='hidden' name='typeCM' value="CM-APP">
          <input type='hidden' name='typeTD' value="TD2">
          <input type='hidden' name='typeTP' value="">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TD2 - parcours A</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '5', 'A', 'CM-APP', 'TD2') ?></td>
        <td class='w3-border'>au moins <?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="5">
          <input type='hidden' name='app' value="B">
          <input type='hidden' name='typeCM' value="CM-APP">
          <input type='hidden' name='typeTD' value="TD3">
          <input type='hidden' name='typeTP' value="">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TD3 - parcours B</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '5', 'B', 'CM-APP', 'TD3') ?></td>
        <td class='w3-border'>au moins <?= toFloat($week) ?> semaines</td>
      </tr>
    </tbody>
  </table>

  <div class="w3-border w3-margin-top w3-margin-bottom"></div>
  <h3 class="w3-margin-top"><b>Semestre 6 FI</b></h3>
  <table class='w3-table w3-bordered w3-border w3-margin-bottom'>
    <thead class='w3-light-gray'>
      <tr>
        <th>Groupes</th>
        <th>Nombre d'heures</th>
        <th>Nombre de semaines (pour 30h/semaine)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="6">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD1">
          <input type='hidden' name='typeTP' value="TPA">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPA - parcours A</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '6', '', 'CM', 'TD1', 'TPA') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="6">
          <input type='hidden' name='typeCM' value="CM">
          <input type='hidden' name='typeTD' value="TD1">
          <input type='hidden' name='typeTP' value="TPB">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TPB - parcours A</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '6', '', 'CM', 'TD1', 'TPB') ?></td>
        <td class='w3-border'><?= toFloat($week) ?> semaines</td>
      </tr>
    </tbody>
  </table>

  <div class="w3-border w3-margin-top w3-margin-bottom"></div>
  <h3 class="w3-margin-top"><b>Semestre 6 APP</b></h3>
  <table class='w3-table w3-bordered w3-border w3-margin-bottom'>
    <thead class='w3-light-gray'>
      <tr>
        <th>Groupes</th>
        <th>Nombre d'heures</th>
        <th>Nombre de semaines (pour 30h/semaine)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="6">
          <input type='hidden' name='app' value="A">
          <input type='hidden' name='typeCM' value="CM-APP">
          <input type='hidden' name='typeTD' value="TD2">
          <input type='hidden' name='typeTP' value="">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TD2 - parcours A</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '6', 'A', 'CM-APP', 'TD2') ?></td>
        <td class='w3-border'>au moins <?= toFloat($week) ?> semaines</td>
      </tr>
      <tr>
        <td class='w3-border'>
          <form method='GET'>
          <input type='hidden' name='page' value='stats'>
          <input type='hidden' name='semester' value="6">
          <input type='hidden' name='app' value="B">
          <input type='hidden' name='typeCM' value="CM-APP">
          <input type='hidden' name='typeTD' value="TD3">
          <input type='hidden' name='typeTP' value="">
          <input type='hidden' name='action' value='groupes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>TD3 - parcours B</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'><?php $week = getTime($db, '6', 'B', 'CM-APP', 'TD3') ?></td>
        <td class='w3-border'>au moins <?= toFloat($week) ?> semaines</td>
      </tr>
    </tbody>
  </table>

</div>
<?php
include "$root/inc/footer.php";
?>

