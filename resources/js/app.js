require('./bootstrap');

window.Vue = require('vue');

import iosAlertView from 'vue-ios-alertview';
Vue.use(iosAlertView);

import ChatComponent from './components/ChatComponent';
import UsersComponent from './components/UsersComponent';
import FirmComponent from './components/FirmComponent';
import ProfileComponent from './components/ProfileComponent';
import AdduserComponent from './components/AdduserComponent';

Vue.component('chat-component', ChatComponent);
Vue.component('users-component', UsersComponent);
Vue.component('firm-component', FirmComponent);
Vue.component('profile-component', ProfileComponent);
Vue.component('adduser-component', AdduserComponent);


const app = new Vue({
    el: '#app',
    data() {
        return {
            user: AuthUser
        }
    },
    methods: {
        userCan(permission) {
            if(this.user && this.user.allPermissions.includes(permission)) {
                return true;
            }
            return false;
        },
        MakeUrl(path) {

            return BaseUrl(path);
        }
    }
});
