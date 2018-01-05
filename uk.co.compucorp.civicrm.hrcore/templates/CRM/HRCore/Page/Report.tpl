{*<script>*}
  {*{literal}*}
  {*function resizeIframe(obj) {*}
    {*obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';*}
  {*}*}
  {*{/literal}*}
{*</script>*}
<iframe id="reportIframe" src="/reports/leave_and_absence/" frameborder="0" scrolling="no" style="width: 100%"></iframe>
<script type="text/javascript">
  {literal}
  CRM.$(function() {
    jQuery('#reportIframe').iFrameResize()
  });
  {/literal}
</script>
