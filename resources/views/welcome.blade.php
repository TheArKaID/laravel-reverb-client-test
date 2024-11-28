{{-- Layout --}}
@extends('app')

@section('title', 'Pusher')

{{-- Body --}}
@section('content')
<section class="section">
    <div class="section-header" style="display: inherit;">
        <h1>Pusher Compatible Tester</h1>
        <br>
        <small>Designed for Laravel Reverb Testing</small>
        <a href="signalr" style="float: right" class="btn btn-primary">SignalR</a>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="message">Message</label>
                                <input type="text" name ="message" id="message" class="form-control" placeholder="New Message">
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="button" id="trigger-event" class="btn btn-primary" onclick="sendTriggerMessage()">Send</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="host">Host</label>
                                    <input type="text" name ="host" id="host" class="form-control" placeholder="Host" value="{{ request('host', 'localhost') }}">
                                    <small>When you run this app from https, then the wss would (looks like) to run on wss, http to ws</small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="port">Port</label>
                                    <input type="text" name ="port" id="port" class="form-control" placeholder="Port" value="{{ request('port', 443) }}">
                                    <small>If you set the port to 80 and 443, it is mean no port</small>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="key">Key</label>
                                    <input type="text" name="key" id="key" class="form-control" placeholder="Key" value="{{ request('key', '') }}">
                                    <small>Key is your client key which to connect</small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="provider">Provider</label>
                                    <select name="provider" class="form-control" id="provider">
                                        <option value="reverb" selected>Reverb</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="connect-btn" class="btn btn-primary" disabled>Connect</button>
                                    <br>
                                    <small>*Submitting will refresh the page with parameter, since Laravel Echo would connect immediately after the page opened</small>
                                </div>
                            </div>
                        </form>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="channel">Channel Name</label>
                                <input type="text" id="channel" class="form-control" placeholder="Channel Name">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="event">Event Name</label>
                                <input type="text" id="event" class="form-control" placeholder="Event Name">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" id="listen-btn" onclick="listen()" disabled>Listen</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-row">
                            <label for="data">Responses</label><br>
                            <textarea class="form-control" id="data" rows="6" style="height: unset" hidden></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')

<script>
    sendTriggerMessage = () => {
        document.getElementById('trigger-event').disabled = true
        const message = document.getElementById('message').value
        axios.post('/api/trigger', { message })
            .then(res => {
                alert(JSON.stringify(res.data.message))
            })
            .catch(err => {
                alert(JSON.stringify(err.message))
            })
            .finally(() => {
                document.getElementById('trigger-event').disabled = false
            })
    }

    var host = document.getElementById('host');
    var port = document.getElementById('port');
    var key = document.getElementById('key');
    var provider = document.getElementById('provider');
    tryWebsocketConnection()
    window.echoConfig = {
        broadcaster: provider.value,
        key: key.value,
        host: host.value,
        port: port.value,
        forceTLS: false
    };
    var editor

    // Ready
    document.addEventListener('DOMContentLoaded', function() {
        initPusherListener()

        var channelName = ''
        var eventName = ''
        var data = ''

        textarea = document.getElementById('data');
        editor = window.CodeMirror.fromTextArea(textarea, {
            lineNumbers: true,
            mode: 'javascript',
            json: true,
            theme: 'duotone-dark',
            readOnly: true,
            scrollbarStyle: 'null',
        });
    });

    function listen() {
        channelName = document.getElementById('channel');
        eventName = document.getElementById('event');
        textarea.hidden = false;

        var channel = Echo.channel(channelName.value);

        channel.listen('pusher:pong', (data) => {
            appendToEditor(data)
        });
        channel.listenToAll((event, data) => {
            // do what you need to do based on the event name and data
            console.log(event, data)
        });
        channel.listen(eventName.value, (data) => {
            appendToEditor(data)
        });
    }

    function initPusherListener() {
        const pusher = window.Echo.connector.pusher

        // Listen for the 'connected' event
        pusher.connection.bind('connected', function(res) {
            res.status = 'connected'
            appendToEditor(res)
        });
        
        // Listen for the 'disconnected' event
        pusher.connection.bind('disconnected', (res) => {
            appendToEditor({
                status: 'disconnected',
                message: 'Connection disconnected, please check your connection or the server is down.'
            })
        });

        // Listen for the 'error' event
        pusher.connection.bind('error', (err) => {                
            appendToEditor(err)
        });
    }

    function appendToEditor(params) {
        editor.setValue(editor.getValue() + '\n' + JSON.stringify(params, null, 2));
    }

    function tryWebsocketConnection() {
        try {
            const isHttps = window.location.protocol === 'https:'
            const ws = new WebSocket(isHttps ? 'wss' : 'ws' + '://' + host.value + ':' + port.value + '/app/' + key.value)

            ws.onopen = function() {
                ws.close()
            }
            ws.onerror = function(err) {
                appendToEditor({
                    status: 'error',
                    message: 'Connection failed, please check your connection or the server is down.'
                })
                ws.close()
            }
        } catch (error) {
            appendToEditor({
                status: 'error',
                message: 'Connection failed, please check your connection or the server is down.'
            })
            ws.close()
        } finally {
            document.getElementById('connect-btn').disabled = false
            document.getElementById('listen-btn').disabled = false
        }
    }
</script>
@endpush