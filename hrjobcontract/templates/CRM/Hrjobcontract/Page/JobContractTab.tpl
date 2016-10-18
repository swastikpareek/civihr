{assign var="module" value="hrjob-contract" }
{assign var="prefix" value="hrjc-" }

<div id="bootstrap-theme" hrjc-loader hrjc-loader-show="true">
  <div id="{$module}">
    <div class="container" ng-view></div>
  </div>
</div>
{literal}
<script type="text/javascript">
  document.addEventListener('hrjcReady', function () {
    angular.bootstrap(document.getElementById('hrjob-contract'), ['hrjc']);
  });
</script>
{/literal}
