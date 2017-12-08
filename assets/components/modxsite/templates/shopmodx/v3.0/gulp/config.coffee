dest = "./bundle"
src = './src'

_ = require 'lodash-node'
fs = require 'fs'

list = fs.readdirSync "#{__dirname}/../src/entries"

multipleBundles = []
_(list).each (file) ->

  _file = file.split('.')
  _file[_file.length - 1] = 'js'

  multipleBundles.push {
    dest: dest
    entries: "#{src}/entries/#{file}"
    outputName: _file.join('.')
  }

module.exports =
  scripts:
    src : "#{src}/vendor/**"
    dest: "#{dest}/vendor"

  less:
    src : "#{src}/styles/styles.less"
    dest: "#{dest}/styles"

  images:
    src : "#{src}/images/**/*"
    dest: "#{dest}/images"

  dist:
    root: dest

  browserify:
    # enable full-paths
    fullPaths: no
    # // Enable source maps
    debug: false
    # // Additional file extentions to make optional
    extensions: ['.coffee', '.cjsx', '.jsx']
    # // A separate bundle will be generated for each
    # // bundle config in the list below
    bundleConfigs: multipleBundles
