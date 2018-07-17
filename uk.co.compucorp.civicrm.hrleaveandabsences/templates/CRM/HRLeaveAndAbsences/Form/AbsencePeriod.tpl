<div id="bootstrap-theme">
  <div class="panel panel-default crm-form-block crm-absence_type-form-block crm-leave-and-absences-form-block">
    <div class="panel-heading">
      <h1 class="panel-title">
        {if $action eq 1}{ts}New Absence Period{/ts}
        {elseif $action eq 2}{ts}Edit Absence Period{/ts}{/if}
      </h1>
    </div>

    <div class="panel-body">
      <div class="form-group row">
        <div class="col-sm-3">{$form.title.label}</div>
        <div class="col-sm-9">{$form.title.html}</div>
      </div>
      <div class="form-group row">
        <div class="col-sm-3">{$form.start_date.label}</div>
        <div class="col-sm-9">{$form.start_date.html}</div>
      </div>
      <div class="form-group row">
        <div class="col-sm-3">{$form.end_date.label}</div>
        <div class="col-sm-9">{$form.end_date.html}</div>
      </div>
      <div class="form-group row">
        <div class="col-sm-3">{$form.weight.label}</div>
        <div class="col-sm-9">{$form.weight.html}</div>
      </div>
    </div>
    <script type="text/javascript">
      {literal}
        CRM.$(document).on('hrappready.formabsenceperiod', function (event, app) {
          (new app.Form.AbsencePeriod());
        });
      {/literal}
    </script>
    <div class="panel-footer clearfix">
      <div class="pull-right">
        {include file="CRM/common/formButtons.tpl" location="bottom"}
      </div>
    </div>
  </div>
</div>
