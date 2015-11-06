<div class="crm-content-block crm-block">
  <div id="help">
    The floating cases are listed below. These are cases of types {$configCaseTypes} that are not linked to a project.
    You can link them to a project with the link action.
  </div>
  {include file='CRM/common/jsortable.tpl'}
  <div id="floating-case-wrapper" class="dataTables_wrapper">
    <table id="floating-case-table" class="display">
      <thead>
        <tr>
          <th>{ts}Customer{/ts}</th>
          <th>{ts}Case Type{/ts}</th>
          <th>{ts}Subject{/ts}</th>
          <th>{ts}Activity Start Date{/ts}</th>
          <th>{ts}Representative{/ts}</th>
          <th>{ts}Project Officer{/ts}</th>
          <th id="nosort"></th>
        </tr>
      </thead>
      <tbody>
      {assign var="rowClass" value="odd-row"}
      {foreach from=$floatingCases item=case}
        <tr id="row1" class={$rowClass}>
          <td hidden="1">{$case.case_id}</td>
          <td hidden="1">{$case.customer_id}</td>
          <td hidden="1">{$case.representative_id}</td>
          <td hidden="1">{$case.project_officer_id}</td>
          <td>{$case.customer}</td>
          <td>{$case.case_type}</td>
          <td>{$case.subject}</td>
          <td>{$case.act_start_date}</td>
          <td>{$case.representative}</td>
          <td>{$case.project_officer}</td>
          <td>
              <span>
                {foreach from=$case.actions item=actionLink}
                  {$actionLink}
                {/foreach}
              </span>
          </td>
        </tr>
        {if $rowClass eq "odd-row"}
          {assign var="rowClass" value="even-row"}
        {else}
          {assign var="rowClass" value="odd-row"}
        {/if}
      {/foreach}
      </tbody>
    </table>
  </div>
</div>
