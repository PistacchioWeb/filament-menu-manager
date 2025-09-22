const preset = require('../../vendor/filament/filament/tailwind.config.preset')
// const preset = require('./vendor/filament/filament/resources/js')

module.exports = {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
