
<div class="db-main">

  <div class="db-header">
      <h2>Discussion Board</h2>
  </div>

  <div class="db-navigation">
      <a class="btn-new-save" style="cursor:pointer"><?php p($l->t('NEW TOPIC')); ?></a>
  </div>

  <div class="db-new-topic-bg" style="display:none">
  </div>
  <div class="db-new-topic" style="display:none">
      <div class="db-new-topic-header">
        <a class="btn-close-new-topic" style="cursor:pointer"><?php p($l->t('CLOSE')); ?></a>
      </div>

      <div class="db-new-topic-content">
      </div>

      <div class="db-new-topic-footer">
      </div>
  </div>

  <div class="db-content">
    <table class="db-content-table">
                <tr class="db-topics-header">
                    <td class="db-topics-header-td"> <?php  p($l->t('Topic')); ?> </td>
                    <td class="db-topics-header-td" style="width:250px"> <?php  p($l->t('Category')); ?> </td>
                    <td class="db-topics-header-td" style="width:250px"> <?php  p($l->t('Author')); ?> </td>
                    <td class="db-topics-header-td" style="width:100px; text-align: center"> <?php  p($l->t('Replies')); ?> </td>
                    <td class="db-topics-header-td" style="width:100px; text-align: center" ><?php  p($l->t('Views')); ?> </td>
                    <td class="db-topics-header-td" style="width:150px; text-align: center"> <?php  p($l->t('Activity')); ?> </td>
                </tr>

                <tr>
                  <td colspan="6"><div class="db-topics-content"></div></td>
                </tr>
                <tr>
                  <td class="db-footer" colspan="6">powered by Denis Becker 2017</td>
                </tr>
    </table>

  </div>

</div>
