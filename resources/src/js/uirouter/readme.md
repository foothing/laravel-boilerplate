Workaround
https://github.com/ui-router/sticky-states/issues/4#issuecomment-327779527

Sticky state won't ship anymore for non-ts library.
So, solution was like
1. add ui-router-angularjs.min.js instead of angular-ui-router
2. add the ui router core
3. add the sticky state plugin


1 is bundled with uirouter, 2 and 3 needs to be built separately.
Use either the linked comment to build, or this https://github.com/ui-router/sticky-states/tree/master/examples/angularjs-npm-script-tags
example bundled in the sticky-states package.
