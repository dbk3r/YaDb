<div class="db-main">
<div class="db-notification" id="db-notification"></div>
  <div class="db-header">
      <h2>Discussion Board</h2>
  </div>

  <div class="db-navigation">
    <table width="100%" class="db-navigation-td">
      <tr class="db-navigation-tr">
        <td class="db-navigation-td"><input placeholder="Search" name="db-search" class="db-input" id="db-search"></td>
        <td class="db-navigation-td" style="text-align:center;" width="200"><a class="btn" id="btn-new-topic" style="cursor:pointer"><?php p($l->t('NEW TOPIC')); ?></a></td>
      </tr>
    </table>
  </div>

  <div class="db-new-topic-bg" style="display:none">
  </div>
  <div class="db-new-topic" style="display:none">
      <div class="db-new-topic-header">
        <a class="btn" id="btn-close-topic" style="cursor:pointer"><?php p($l->t('CLOSE')); ?></a>
      </div>

      <div class="db-new-topic-content">
        <div class="db-new-topic-content-header"></div>
        <div class="db-new-topic-content-editor"><textarea id="db-new-topic-editor"></textarea></div>
      </div>

      <div class="db-new-topic-footer">
        <button class="btn_newreplysave" id="btn_newreplysave">apply</button>
      </div>
  </div>

  <div class="db-content">
    <table class="db-content-table">   
                <tr>
                  <td colspan="5"><div class="db-topic-div"></div></td>
                </tr>
                <tr>
                  <td class="db-footer" colspan="6">powered by Denis Becker 2017</td>
                </tr>
    </table>

  </div>

</div>
