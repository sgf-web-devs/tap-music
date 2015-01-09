<header class="chat_header">
    <h1>Chat <i class="fa fa-comments"></i></h1>
    <aside>
        <a class="disableNotification" href="#"><i class="fa"></i></a>
    </aside>
</header>
<div class="chat_log">
    <article ng-repeat="message in chatLog">
        <figure>
            <img ng-src="{{ message.image }}" alt=""/>
        </figure>
        <section>
            <header>
                <h1>{{ message.user }}</h1>
                <h2><i class="fa fa-clock-o"></i> {{ message.time }}</h2>
            </header>
            <div class="entry">
                {{ message.message }}
            </div>
        </section>
    </article>
</div>
<footer>
    <form action="/pusher/send-chat-message" method="post">
        <div class="input-group">
            <input id="btn-input" type="text" class="form-control input-sm" placeholder="Start typing..." name="message" autocomplete="off" required />
            <span class="input-group-btn">
                <button class="btn btn-sm" id="btn-chat" type="submit">Send</button>
            </span>
        </div>
    </form>
</footer>

<!--<ul ng-sortable="{animation:150}">
    <li ng-repeat="item in localQueue">
        {{item.trackName}}
    </li>
</ul>-->