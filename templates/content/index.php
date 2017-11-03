<input type="hidden" name="nextNonce" id="nextNonce" value="<?php p(\OC::$server->getContentSecurityPolicyNonceManager()->getNonce()) ?>" />

<div class="db-main">

  <div class="db-header">
      <h2>Discussion Board</h2>
  </div>

  <div class="db-navigation">
    <table width="100%" class="db-navigation-td">
      <tr class="db-navigation-tr">
        <td class="db-navigation-td"><input placeholder="Search" name="db-search" class="db-input"></td>
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
                <tr class="db-topics-header">
                    <td class="db-topics-header-td"> <?php  p($l->t('Topic')); ?> </td>
                    <td class="db-topics-header-td" style="width:250px"> <?php  p($l->t('Category')); ?> </td>
                    <td class="db-topics-header-td" style="width:100px; text-align: center"> <?php  p($l->t('Replies')); ?> </td>
                    <td class="db-topics-header-td" style="width:100px; text-align: center" ><?php  p($l->t('Views')); ?> </td>
                    <td class="db-topics-header-td" style="width:150px; text-align: center"> <?php  p($l->t('Activity')); ?> </td>
                </tr>

                <tr>
                  <td colspan="5"><div class="db-topics-content"></div></td>
                </tr>
                <tr>
                  <td class="db-footer" colspan="6">powered by Denis Becker 2017</td>
                </tr>
    </table>

  </div>

</div>
