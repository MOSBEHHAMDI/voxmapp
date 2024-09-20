import Setting from "@/mobile/views/Setting";
import History from "@/mobile/views/UserHistory";
import UserHistoryDetails from "@/mobile/views/UserHistoryDetails";
import userTasks from "@/mobile/views/userTasks.vue";
import dataPoints from "@/mobile/views/DataPoints";
import DataPointRegistry from "@/mobile/views/DataPointRegistry.vue";
import ProjectInfo from "@/mobile/components/ProjectInfo.vue";
import Login from "@/mobile/views/Login";
import Home from "@/mobile/views/Home";
import Baseline from "@/mobile/views/Baseline";
import EditProfile from "@/mobile/views/EditProfile";
import ViewDataPoint from "@/mobile/views/ViewDataPoint.vue";
import Questionnaire from "@/mobile/views/Questionnaire.vue";
import Report from "@/mobile/views/Report.vue";
import Chat from "@/mobile/views/ChatList.vue";
import ChatMessages from "@/mobile/views/ChatMessages.vue";

export default [
    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/baseline',
        component: Baseline,
        props: {
            app_bar: true,
            bottom_navigation: true
        }
    },
    {
        path: '/home',
        component: Home,
        props: {
            app_bar: false,
            bottom_navigation: false
        }
    },
    {
        path: '/login',
        component: Login,
        name: 'Login',
        props: {
            app_bar: false,
            bottom_navigation: false
        }
    },
    {
        path: '/map',
        component: Baseline,
        props: {
            app_bar: true,
            bottom_navigation: true
        }
    },
    {
        path: '/settings',
        component: Setting,
        props: {
            app_bar: true,
            app_bar_title: 'Settings',
            bottom_navigation: true
        }
    },
    {
        path: '/history',
        component: History,
        props: {
            app_bar: true,
            app_bar_title: 'Daily actions',
            dynamicSubTitle: true,
            bottom_navigation: true
        }
    },
        {
        path: '/chat',
        component: Chat,
        props: {
            app_bar: true,
            app_bar_title: 'Chat',
             bottom_navigation: true
        }
    },
        {
        path: '/ChatMessages',
        name: 'ChatMessages',
         component: ChatMessages,
         props: {
            app_bar: true,
            dynamicTitle: true,
            optionsButton: true,
        }
    }
    ,
    {
        path: '/UserHistoryDetails',
        component: UserHistoryDetails,
        props: {
            app_bar: true,
            bottom_navigation: true,
            tabsHeader: ['Map', 'List'],
        }
    },
    {
        path: '/userTasks',
        component: userTasks,
        props: {
            app_bar: true,
            bottom_navigation: true,
            tabsHeader: ['Personal', 'Projects'],
        }
    },
    {
        path: '/dataPoints',
        component: dataPoints,
        props: {
            app_bar: true,
            bottom_navigation: true,
            app_bar_title: 'Data Points registry'
        }
    },
    {
        path: '/dataPointRegistry',
        component: DataPointRegistry,
        props: {
            app_bar: true,
            bottom_navigation: true,
            app_bar_title: 'Edit Data Point'
        }
    },
    {
        path: '/projectInfo',
        name: 'ProjectInfo',
        component: ProjectInfo,
        props: {
            app_bar: true,
            bottom_navigation: true,
            dynamicTitle: true,
            optionsButton: true,
        }
    }
    ,
    {
        path: '/edit_profile',
        component: EditProfile,
        props: {
            app_bar: true,
            app_bar_title: 'Edit Profile',
            save: true,
            bottom_navigation: true
        }
    },
    {
        path: '/view_data_point',
        name: 'ViewDataPoint',
        component: ViewDataPoint,
        props: {
            app_bar: true,
            dynamicTitle: true,
            optionsButton: true,
        }

    },
    {
        path: '/questionnaire',
        name: 'Questionnaire',
        component: Questionnaire,
        props: true
    },
    {
        path: '/report',
        component: Report,
        props: {
            app_bar: true,
            app_bar_title: 'Report',
        }
    },
]

