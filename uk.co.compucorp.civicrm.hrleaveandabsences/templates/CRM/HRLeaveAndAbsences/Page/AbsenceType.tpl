{if $action eq 1 or $action eq 2 or $action eq 8}
  {include file="CRM/HRLeaveAndAbsences/Form/AbsenceType.tpl"}
{else}
  {if $rows}
    <div id="bootstrap-theme" class="crm-leave-and-absences-list-block">
      <div class="alert alert-info">
        {ts}Some leave/absence types cannot be deleted because there are existing absences of that type.{/ts}
      </div>
      <div class="panel panel-default">
        {strip}
          {* handle enable/disable actions*}
          {include file="CRM/common/enableDisableApi.tpl"}
          <table cellpadding="0" cellspacing="0" border="0" class="table table-responsive hrleaveandabsences-entity-list">
            <thead class="sticky">
              <th>{ts}Title{/ts}</th>
              <th>{ts}Allow Accruals?{/ts}</th>
              <th>{ts}Is default?{/ts}</th>
              <th>{ts}Order{/ts}</th>
              <th>{ts}Enabled/Disabled{/ts}</th>
              <th>{ts}Actions{/ts}</th>
            </thead>
            {foreach from=$rows item=row}
              <tr id="AbsenceType-{$row.id}" class="crm-entity {if NOT $row.is_active} disabled{/if}">
                <td data-field="title">{$row.title|escape}</td>
                <td>{if $row.allow_accruals_request eq 1} {ts}Yes{/ts} {else} {ts}No{/ts} {/if}</td>
                <td>{if $row.is_default eq 1}<i class="fa fa-check"></i>{/if}</td>
                <td>{$row.weight}</td>
                <td>{if $row.is_active eq 1} {ts}Enabled{/ts} {else} {ts}Disabled{/ts} {/if}</td>
                <td>{$row.action|replace:'xx':$row.id}</td>
              </tr>
            {/foreach}
          </table>
        {/strip}
        {if $action ne 1 and $action ne 2}
          <div class="panel-footer clearfix">
            <div class="pull-right">
              <a href="{crmURL q="action=add&reset=1"}" class="btn btn-primary">
                <i class="fa fa-plus btn-icon"></i>
                <span>{ts}Add Leave/Absence Type{/ts}</span>
              </a>
            </div>
          </div>
        {/if}
      </div>
    </div>
    <script type="text/javascript">
      {literal}
        CRM.$(document).on('hrappready.list', function(event, app) {
          (new app.ListPage(CRM.$('.hrleaveandabsences-entity-list')));
        });
      {/literal}
    </script>
  {else}
    <div id="bootstrap-theme">
      <div class="alert alert-info">
        <div class="icon inform-icon"></div>
        {capture assign=crmURL}{crmURL q="action=add&reset=1"}{/capture}
        {ts 1=$crmURL}There are no Leave/Absence Types entered. You can <a href='%1'>add one</a>.{/ts}
      </div>
    </div>
  {/if}
{/if}
