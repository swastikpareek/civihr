<div id="bootstrap-theme" class="manage-entitlements-form">
  <div class="panel panel-default crm-form-block">
    <div class="panel-body">
      <p>{ts}<strong>WARNING:</strong> Please note that any currently stored annual entitlement allowance for the selected staff member(s) will be overwritten by this process{/ts}</p>
      <hr class="wider-hr" />
      <div class="manage-entitlements-form__filters row entitlement-calculation-filters">
        {* These hidden fields are used when we submit the form to export the CSV file *}
        {* The id is used so we know from which period we are exporting the CSV *}
        {* The cid is used so can export calculations only for the specified contacts *}
        <input type="hidden" name="export_csv" id="export_csv" value="0">
        <input type="hidden" name="id" id="period_id" value="{$period->id}">
        {foreach from=$contactsIDs item=id}
          <input type="hidden" name="cid[]" value="{$id}">
        {/foreach}

        <div class="col-sm-4 col-sm-offset-4">
          <div class="btn-group" data-toggle="buttons">
            <button class="btn btn-sm btn-secondary-outline">
              <input type="radio" name="override-filter" class="override-filter" value="1">
              <span>Overriden</span>
            </button>
            <button class="btn btn-sm btn-secondary-outline">
              <input type="radio" name="override-filter" class="override-filter" value="2">
              <span>Not Overridden</span>
            </button>
            <button class="btn btn-sm btn-secondary-outline active">
              <input type="radio" name="override-filter" class="override-filter" value="3" checked>
              <span>Both</span>
            </button>
          </div>
        </div>

        <div class="col-sm-4">
          <div class="absence-type-filter row">
            <div class="col-md-9">
              <select name="absence-type-filter" id="absence_type_filter" class="crm-select2 form-control absence-type-filter" multiple="multiple" data-placeholder="{ts}Leave Type{/ts}">
                {foreach from=$enabledAbsenceTypes item=absenceType}
                  <option value="{$absenceType->id}">{$absenceType->title|escape}</option>
                {/foreach}
              </select>
            </div>
            <div class="col-md-3 text-center">
              <a href="{crmURL q="id=`$period->id`&csv=1&reset=1"}" class="export-csv-action">{ts}Export CSV{/ts}</a>
            </div>
          </div>
        </div>
      </div>

      <div class="row wider-row">
        <table class="table table-condensed entitlement-calculation-list">
          <thead>
          <tr>
            <th>{ts}Employee ID{/ts}</th>
            <th>{ts}Employee name{/ts}</th>
            <th>{ts}Leave type{/ts}</th>
            <th>{ts}Previous period entitlement{/ts}</th>
            <th>{ts}Previous period Accrued TOIL{/ts}</th>
            <th>{ts}Used in previous period{/ts}</th>
            <th>{ts}Remaining{/ts}</th>
            <th>{ts}Brought Forward from previous period{/ts}</th>
            <th>{ts}New Period Pro rata{/ts}</th>
            <th class="proposed-entitlement-header">
              <div class="row">
                <div class="col-sm-8">
                  {ts}New Proposed Period Entitlement{/ts}
                </div>
                <div class="col-sm-2 action-button-container">
                  <button type="button" class="add-one-day borderless-button" title="{ts}Add an extra day to all contacts listed{/ts}">
                    <i class="fa fa-plus-square-o"></i>
                  </button>
                </div>
                <div class="col-sm-2 action-button-container">
                  <button type="button" class="copy-to-all borderless-button" title="{ts}Copy the new proposed entitlement from top row to all others{/ts}">
                    <i class="fa fa-copy"></i>
                  </button>
                </div>
              </div>
            </th>
            <th>{ts}Comment{/ts}</th>
          </tr>
          </thead>
          <tbody>
          {foreach from=$calculations item=calculation}
            {assign var=absenceType value=$calculation->getAbsenceType()}
            {assign var=absenceTypeID value=$absenceType->id}
            {assign var=absencePeriod value=$calculation->getAbsencePeriod()}
            {assign var=absencePeriodID value=$absencePeriod->id}
            {assign var=contact value=$calculation->getContact()}
            {assign var=calculationUnit value='days'}
            {assign var=calculationUnitSuffix value='d'}
            {if $calculation->isCalculationUnitInHours()}
              {assign var=calculationUnit value='hours'}
              {assign var=calculationUnitSuffix value='h'}
            {/if}
            <tr data-contact="{$contact.id}" data-absence-type="{$absenceTypeID}" data-absence-period="{$absencePeriodID}">
              <td>{$contact.id}</td>
              <td>{$contact.display_name|escape}</td>
              <td>
                <span class="absence-type" style="background-color: {$absenceType->color};">
                  {$absenceType->title|escape}
                </span>
              </td>
              <td>{$calculation->getPreviousPeriodProposedEntitlement()|timeUnitApplier:$calculationUnit}</td>
              <td>{$calculation->getAccruedTOILForPreviousPeriod()|timeUnitApplier:$calculationUnit}</td>
              <td>{$calculation->getAmountUsedInPreviousPeriod()|timeUnitApplier:$calculationUnit}</td>
              <td>{$calculation->getPreviousPeriodBalance()|timeUnitApplier:$calculationUnit}</td>
              <td>{$calculation->getBroughtForward()|timeUnitApplier:$calculationUnit}</td>
              <td>{$calculation->getProRata()|timeUnitApplier:$calculationUnit}</td>
              <td class="proposed-entitlement">
                  {assign var=proposedEntitlement value=$calculation->getProposedEntitlement()}
                  <span class="proposed-value" data-raw-value="{$proposedEntitlement}">
                    {$proposedEntitlement|timeUnitApplier:$calculationUnit}
                  </span>
                  {$form.overridden_entitlement[$contact.id][$absenceTypeID].html}
                  <span class="calculation-unit"> {$calculationUnitSuffix} </span>
                  <button type="button" class="borderless-button"><i class="fa fa-pencil"></i></button>
                  <label for="override_checkbox_{$contact.id}_{$absenceTypeID}">
                    <input id="override_checkbox_{$contact.id}_{$absenceTypeID}"
                           type="checkbox"
                           class="override-checkbox"
                           {if $calculation->isCurrentPeriodEntitlementOverridden()}checked{/if}> Override
                  </label>
              </td>
              <td class="comment">
                {$form.comment[$contact.id][$absenceTypeID].html}
                <button type="button" class="borderless-button add-comment"><i class="fa fa-share-square-o"></i></button>
              </td>
            </tr>
          {/foreach}
          </tbody>
        </table>
      </div>
    </div>

    <div class="panel-footer clearfix">
      <div class="pull-right">
        <a href="{$returnUrl}" class="button"><span>{ts}Back{/ts}</span></a>
        {include file="CRM/common/formButtons.tpl" location="bottom"}
      </div>
    </div>

    <div id="add-comment-dialog" title="{ts}Add/Edit comment{/ts}">
      <p>{ts}You can leave a comment as a record of your calculation for the leave entitlement for this period. Comments are then shown as tooltips on the leave entitlement on the contact record for administrators to refer back to.{/ts}</p>
      <textarea name="calculation_comment" class="calculation_comment form-control" cols="30" rows="10"></textarea>
    </div>
  </div>
</div>

<script type="text/javascript">
  {literal}
    CRM.$(document).on('hrappready.formmanageentitlements', function(event, app) {
      (new app.Form.ManageEntitlements());
    });
  {/literal}
</script>
