@extends('layouts.app')

@section('title', 'Blank Page')

@push('styles')
    <!-- CSS -->
@endpush

@section('main')
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
                                        <label for="channel">Channel Name</label>
                                        <input type="text" id="channel" class="form-control" placeholder="Channel Name">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="event">Event Name</label>
                                        <input type="text" id="event" class="form-control" placeholder="Event Name">
                                    </div>
                                    <button class="btn btn-primary" onclick="listen()">Listen</button>
                                </div>
                            {{-- <div class="card-footer">
                                <textarea class="form-control" id="data" rows="6" style="height: unset" hidden></textarea>
                            </div> --}}
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
@endsection

@push('scripts')
    <script>
        var channelName = ''
        var eventName = ''
        var data = ''

        window.echoConfig = {
            broadcaster: 'reverb',
            key: 'grosphere-dev',
            host: 'dev.grosphere.sg',
            port: 443,
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
@endpush