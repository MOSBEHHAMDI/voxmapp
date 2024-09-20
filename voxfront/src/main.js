/**
 * main.js
 *
 * Bootstraps Vuetify and other plugins then mounts the App`
 */

// Composables
import {createApp} from 'vue'

// Plugins
import {registerPlugins} from '@/plugins/plugins'
import {createRouter, createWebHistory} from "vue-router";

const buildApp = async () => {
    const env = import.meta.env.VITE_APP_ENV
    const {default: App} = await import(`./${env}/App.vue`);
    const {default: routes} = await import(`./${env}/router/router.js`)
    const router = createRouter({
        history: createWebHistory(process.env.BASE_URL),
        routes,
    });
    import(`./${env}/styles/app.css`)

    const myGlobalMixin = {
        data() {
            return {
                downloadedFiles: {},
                usersItems: [],
                avatars: [],// data variable to store avatars indexed by user ID
            };
        },
        computed: {
            global_rules() {
                return {
                    required: value => {
                        if (value) return true;
                        return this.tr('This field is required');
                    }
                };
            },
            getUsersItems() {
                this.usersItems = [];
                for (const history of this.usersHistory) {
                    const userId = history.user.id;
                    if (!this.avatars[userId]) {
                        // Fetch avatar only if it hasn't been fetched before
                        this.$store.downloadFile(history.user.profilePic, "https://avatars0.githubusercontent.com/u/9064066?v=4&s=460").then(resp => {
                            this.avatars[userId] = resp;
                        }).catch(error => {
                            console.error("Error fetching avatar:", error);
                        });
                    }
                    const u = {
                        prependAvatar: this.avatars[userId],
                        title: history.user.userName,
                        subtitle: history.user.email,
                        date: this.$utils.formatDate(history.date, '-'),
                    };
                    this.usersItems.push(u);
                }
                return this.usersItems;
            }
        },
        methods: {
            getDownloadedFiles(medias) {
                this.downloadedFiles = {};
                if (medias) {
                    medias.forEach((media) => {
                        if (typeof this.downloadedFiles[media.id] === 'undefined') {
                            this.$store.downloadFile(media).then((resp) => {
                                this.downloadedFiles[media.id] = resp;
                            });
                        }
                    });
                }
                return this.downloadedFiles;
            },
            tr(param) {
                return param
            },
        },
    };

    const app = createApp(App)
    app.mixin(myGlobalMixin);
    registerPlugins(app, router)
    app.mount('#app')
};
buildApp()