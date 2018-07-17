<div id="bootstrap-theme">
  <div class="work-pattern-form panel panel-default crm-form-block crm-leave-and-absences-form-block">
    <div class="panel-heading">
      <h1 class="panel-title">
        {if $action eq 1}{ts}New Work Pattern{/ts}
        {elseif $action eq 2}{ts}Edit Work Pattern{/ts}{/if}
      </h1>
    </div>
    {if $action neq 8}
      <ul class="nav nav-tabs">
        <li class="active"><a href="#work-pattern-details" data-toggle="tab">{ts}Details{/ts}</a></li>
        <li><a href="#work-pattern-calendar" data-toggle="tab">{ts}Calendar{/ts}</a></li>
      </ul>
      <div class="tab-content">
        <div id="work-pattern-details" class="tab-pane active">
          {include file="CRM/HRLeaveAndAbsences/Form/WorkPattern/Details.tpl"}
        </div>
        <div id="work-pattern-calendar" class="tab-pane">
          {include file="CRM/HRLeaveAndAbsences/Form/WorkPattern/Calendar.tpl"}
        </div>
      </div>
    {/if}
    <div class="panel-footer clearfix">
      <div class="pull-right">
        {include file="CRM/common/formButtons.tpl" location="bottom"}
      </div>
    </div>
  </div>
  <script type="text/javascript">
    {literal}
      CRM.$(function($) {
        $('#bootstrap-theme select').select2('destroy');
      });
    {/literal}
  </script>
</div>
