/*
|-------------------------------------------------------------------------------
| Production config                       https://maizzle.com/docs/environments
|-------------------------------------------------------------------------------
|
| This is where you define settings that optimize your emails for production.
| These will be merged on top of the base config.js, so you only need to
| specify the options that are changing.
|
*/

/** @type {import('@maizzle/framework').Config} */
module.exports = {
  build: {
    templates: {
      source: 'src/mail',
      destination: {
        path: '../resources/views/mail',
        extension: 'blade.php',
      },
      assets: null,
    },
  },
  inlineCSS: true,
  removeUnusedCSS: true,
  shorthandCSS: true,
  prettify: true,
}
