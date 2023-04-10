<template>
    <div class="card">
        <div class="card-header card chat-box card-success">{{ otherUser.name }}</div>

        <div class="card-body chat-content">
            <div v-for="message in messages" v-bind:key="message.id">
                <div
                  :class="{ 'text-right NewUpdate': message.author === authUser.email }"
                >
                <div class="chat-item chat-right" v-if="message.author === authUser.email">
                        
                        <div class="chat-details">
                            <div class="chat-text">{{ message.body }}</div>
                            <div class="chat-time">you</div>
                        </div>
                        
                </div>
                    <div class="chat-item chat-left" v-if="message.author !== authUser.email">
                       
                        <div class="chat-details">
                            <div class="chat-text">{{ message.body }}</div>
                            <div class="chat-time">{{ otherUser.name }}</div>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="card-footer  chat-form">
            <input
                type="text"
                v-model="newMessage"
                class="form-control"
                placeholder="Type your message..."
                @keyup.enter="sendMessage"
            />
        </div>
    </div>
</template>

<script>
export default {
    name: "ChatComponent",
    props: {
        authUser: {
            type: Object,
            required: true
        },
        otherUser: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            messages: [],
            newMessage: "",
            channel: ""
        };
    },
    async created() {
        const token = await this.fetchToken();
        await this.initializeClient(token);
        await this.fetchMessages();
    },
    methods: {
        async fetchToken() {
            const { data } = await axios.post("/api/token", {
                email: this.authUser.email
            });

            return data.token;
        },
        async initializeClient(token) {
            const client = await Twilio.Chat.Client.create(token);

            client.on("tokenAboutToExpire", async () => {
                const token = await this.fetchToken();

                client.updateToken(token);
            });

            
            var cid = `${this.authUser.id}-${this.otherUser.id}`;
            if(this.authUser.id > this.otherUser.id) {
                cid = `${this.otherUser.id}-${this.authUser.id}`;
            }
            this.channel = await client.getChannelByUniqueName(
                cid
            );
            console.log('cid================', cid);
            this.channel.on("messageAdded", message => {
                this.messages.push(message);
                scrollbot()
            });
        },
        async fetchMessages() {
            this.messages = (await this.channel.getMessages()).items;
            console.log('custom_loader================');
            $('.custom_loader').hide();
            $('.custom_loader + div').show();
            scrollbot()
        },
        sendMessage() {
            
            this.channel.sendMessage(this.newMessage);
            var cid = `${this.authUser.id}-${this.otherUser.id}`;
            var jqxhr = $.ajax( "/messages/chatNote/"+cid ).done(function() {console.log( "MSG SENT" );  })
            this.newMessage = "";
            scrollbot()
            
        }
    }
};

function scrollbot(){
    
    setTimeout(function(){
        $(".card-body").scrollTop($(".card-body")[0].scrollHeight+200);     
    },500);
}
</script>
