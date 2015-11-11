{* HEADER *}
<h3>{$formHeader}</h3>
<div class="messages status no-popup">
  <div class="icon inform-icon"></div>
  {ts}A floating case can be linked to any project as long as the customer is the same. If you want to
  link a case to a project of another customer, change the case client first and then use this function.{/ts}
</div>

<div class="crm-block crm-form-block">

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="top"}
  </div>

  <div class="crm-section">
    <div class="label">{$form.case_type.label}</div>
    <div class="content">{$form.case_type.value}</div>
    <div class="clear"></div>
  </div>

  <div class="crm-section">
    <div class="label">{$form.case_status.label}</div>
    <div class="content">{$form.case_status.value}</div>
    <div class="clear"></div>
  </div>

  <div class="crm-section">
    <div class="label">{$form.case_subject.label}</div>
    <div class="content">{$form.case_subject.value}</div>
    <div class="clear"></div>
  </div>

  <div class="crm-section">
    <div class="label">{$form.case_customer.label}</div>
    <div class="content">{$form.case_customer.value}</div>
    <div class="clear"></div>
  </div>

  <div class="crm-section">
    <div class="label">{$form.case_country.label}</div>
    <div class="content">{$form.case_country.value}</div>
    <div class="clear"></div>
  </div>

  <div class="crm-section">
    <div class="label">{$form.case_representative.label}</div>
    <div class="content">{$form.case_representative.value}</div>
    <div class="clear"></div>
  </div>

  <div class="crm-section">
    <div class="label">{$form.case_project_officer.label}</div>
    <div class="content">{$form.case_project_officer.value}</div>
    <div class="clear"></div>
  </div>

  <div class="crm-section">
    <div class="label">{$form.case_project_id.label}</div>
    <div class="content">{$form.case_project_id.html}</div>
    <div class="clear"></div>
  </div>

  {* FOOTER *}
  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
</div>
