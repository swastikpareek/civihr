{if $action eq 1 or $action eq 2 or $action eq 8}
  {include file="CRM/HRLeaveAndAbsences/Form/PublicHoliday.tpl"}
{else}
  <div id="bootstrap-theme" class="crm-leave-and-absences-list-block">
  {if $rows}
    <div class="panel panel-default">
      {strip}
        {* handle enable/disable actions*}
        {include file="CRM/common/enableDisableApi.tpl"}
        <table cellpadding="0" cellspacing="0" border="0" class="table table-responsive hrleaveandabsences-entity-list">
          <thead class="sticky">
            <th>{ts}Title{/ts}</th>
            <th>{ts}Date{/ts}</th>
            <th>{ts}Enabled/Disabled{/ts}</th>
            <th>&nbsp;</th>
          </thead>
          {foreach from=$rows item=row}
            <tr id="PublicHoliday-{$row.id}" class="crm-entity {$row.class}{if NOT $row.is_active} disabled{/if}">
              <td data-field="title">{$row.title|escape}</td>
              <td>{$row.date|crmDate}</td>
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
              <span>{ts}Add Public Holiday{/ts}</span>
            </a>
          </div>
        </div>
      {/if}
    </div>
    <script type="text/javascript">
      {literal}
        CRM.$(document).on('hrappready.list', function(event, app) {
          (new app.ListPage(CRM.$('.hrleaveandabsences-entity-list')));
        });
      {/literal}
    </script>
  {else}
    <div class="alert alert-info">
      <div class="icon inform-icon"></div>
      {capture assign=crmURL}{crmURL q="action=add&reset=1"}{/capture}
      {ts 1=$crmURL}There are no Public Holidays entered. You can <a href='%1'>add one</a>.{/ts}
    </div>
  {/if}
  </div>
{/if}
