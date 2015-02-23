_ = require 'lodash-node'
rsvp = require("rsvp/dist/rsvp.min").RSVP
xhr = require 'superagent'

_debug = (callback) ->
  callback() if location.hash.match '!dev'

_failure = (msg) ->
  _debug ->
    console.warn "Request failure: #{msg}"

Request =
  init: (props = {} ) ->
    props = _.merge
      actionKey: 'pub_action'
      connectorsUrl: no
      connector: no
      dataType: "form"
      eventDelimiter: "/"
      method: 'post'
    , props

    for k, i of props
      Object.defineProperty @, k,
        enumerable: true
        value: i
        writable: true

    @

  getActionUrl: (action = {}) ->
    url = action.url || @connectorsUrl + @connector
    action.key = action.key or @actionKey
    "#{url}?#{action.key}=#{action.name}"

  getEventName: (event, slice = no) ->
    event = event.split @eventDelimiter
    event.shift() if slice
    event.join "."

  getPrefixName: (resp) ->
    prefix = "failure."
    switch @dataType
      when "html"
        prefix = "success."
      else
        prefix = "success." if resp.success

    prefix

  run: (action, data, event, url) ->
    return new rsvp.Promise (resolve, reject) =>

      callback =
        (error, res) =>

          event = @getEventName(
            if (event && event.constructor is String) then event else action
          )

          if error?
            _failure res.error.message
            reject res
          else

            if res.error
              _failure(res.error.message)

              resp = res
              prefix = 'failure.'

            else
              resp = JSON.parse res.text
              prefix = @getPrefixName resp

              @processResponse resp

            resolve
              success: resp.success or no
              event: "#{prefix}#{event}"
              response: resp

      switch @method
        when 'post'
          xhr(@method, @getActionUrl(name: action, url: url))
            .type @dataType
            .send data
            .end callback
        else
          xhr(@method, @getActionUrl(name: action, url: url))
            .type @dataType
            .query data
            .end callback

  processResponse: (resp) ->
    if resp.success
      @onSuccess resp.message, resp
    else
      @onFailure resp.message, resp

  onSuccess: ->
  onFailure: ->

module.exports = Request
