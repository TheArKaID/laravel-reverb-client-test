<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title') - Stisla</title>

    @stack('styles')

    @vite(['resources/js/app.js'])
<body>
    <div id="app">
        <div class="main-wrapper">
            @include('components.header')
            <div class="main-content">
                <section class="section">
                    <div class="section-header" style="display: inherit;">
                        <h1>Pusher Compatible Tester</h1>
                        <br>
                        <small>Designed for Laravel Websocket Testing</small>
                    </div>
        
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="host">Host</label>
                                                <input type="text" id="host" class="form-control" placeholder="Host" value="{{ request('host', 'localhost') }}">
                                                <small>When you run this app from https, then the wss would (looks like) to run on wss, http to ws</small>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="port">Port</label>
                                                <input type="text" id="port" class="form-control" placeholder="Port" value="{{ request('port', 443) }}">
                                                <small>If you set the port to 80 and 443, it is mean no port</small>
                                            </div>
                                        </div>
                                        
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="key">Key</label>
                                                <input type="text" id="key" class="form-control" placeholder="Key" value="{{ request('key', '') }}">
                                                <small>Key is your client key which to connect</small>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="provider">Provider</label>
                                                <select name="provider" class="form-control" id="provider">
                                                    <option value="reverb" selected>Reverb</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <button class="btn btn-primary" onclick="listen()">Connect</button>
                                                <small>Hit the button will refresh the page with parameter, since Laravel Echo would connect immediately the page opened</small>
                                            </div>
                                        </div>
        
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
                                                <button class="btn btn-primary" onclick="listen()">Listen</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
        
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-row">
                                            <textarea class="form-control" id="data" rows="6" style="height: unset" hidden></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <script>
        var host = document.getElementById('host');
        var port = document.getElementById('port');
        var key = document.getElementById('key');
        var provider = document.getElementById('provider');

        var channelName = ''
        var eventName = ''
        var data = ''

        window.echoConfig = {
            broadcaster: provider.value,
            key: key.value,
            host: host.value,
            port: port.value,
            forceTLS: false
        };

        textarea = document.getElementById('data');
        var editor
        function listen() {
            channelName = document.getElementById('channel');
            eventName = document.getElementById('event');
            textarea.hidden = false;
            editor = window.CodeMirror.fromTextArea(textarea, {
                lineNumbers: true,
                mode: 'javascript',
                json: true,
                theme: 'duotone-dark',
                readOnly: true,
                scrollbarStyle: 'null',
            });

            var channel = Echo.channel(channelName.value);
            console.log(channelName.value, eventName.value)
            channel.listen(eventName.value, (data) => {
                // append and new line
                data = editor.getValue() + '\n' + JSON.stringify(data, null, 2);
                editor.setValue(data);
            });
            channel.listenToAll((event, data) => {
                // do what you need to do based on the event name and data
                console.log(event, data)
            });
        }

        // On websocket connected
        function onConnected() {
            console.log('Connected to websocket');
        }
    </script>
</body>

</html>