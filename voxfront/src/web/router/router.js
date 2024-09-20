import Home from '../../web/views/Home.vue'
import Login from '../../web/views/Login.vue'
import QuestionnaireList from '../views/QuestionnaireList.vue'
import TaskList from '../views/TaskList.vue'
import ChatList from '../views/ChatList.vue'
import createTask from '@/web/views/CreateTask'
import DatapointList from '../views/DatapointList.vue'
import DataPointDetails from '@/web/views/DataPointDetails.vue'
import createDataPoint from '@/web/views/CreateDataPoint'
import EditQuestionnaire from '@/web/views/EditQuestionnaire.vue'
import EditProfile from "@/web/views/EditProfile";
import PreviewQuestionnaire from "@/web/views/PreviewQuestionnaire.vue";
import Translation from '../views/Translation.vue'

//import PaymentViewVue from '../views/PaymentView.vue';

const customMatcher = (route) => {
    const match = route.path.match(/^(.+)\/(.*)/);
    if (match) {
        return {
            path: match[1], params: {iri: decodeURIComponent(match[2])},
        };
    }
    return null;
};

export default [
    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/home',
        component: Home,
        name: 'Home',
        meta: {
            breadcrumb: false,
            app_bar: true,
            navigation_drawer: true
        }
    },
    {
        path: '/login',
        component: Login,
        name: 'Login',
        meta: {
            breadcrumb: false,
            app_bar: false,
            navigation_drawer: false
        }
    },
    {
        path: '/questionnaire/list',
        component: QuestionnaireList,
        name: 'Questionnaire List',
    },
    {
        path: '/task/list',
        component: TaskList,
        name: 'Task List',
    },
    {
        path: '/chat/list',
        component: ChatList,
        name: 'Chat List',
    },
    {
        path: '/task/create',
        alias: '/task/edit',
        component: createTask,
        name: 'Task Create',
    },
        {
        path: '/translation',
        alias: '/translation',
        component: Translation,
        name: 'Translation',
    },
    {
        path: '/datapoint/list',
        component: DatapointList,
        name: 'Data Point List',
    },
     {
        path: '/preview_questionnaire',
        component: PreviewQuestionnaire,
        name: 'Preview Questionnaire',
    },
    {
        path: '/datapoint/details',
        component: DataPointDetails,
        name: 'Data Point Details',
        meta: true

    },
    {
        path: '/datapoint/create',
        alias: '/datapoint/edit',
        component: createDataPoint,
        name: 'Data Point Create',
    },
    {
        path: '/questionnaire/edit',
        component: EditQuestionnaire,
        name: 'Edit Questionnaire',
        props: true,
        //matcher: customMatcher
    },
    {
        path: '/profile/edit',
        component: EditProfile,
        name: 'Edit Profile',
        props: true,
        // matcher: customMatcher
    },
    {
        path: '/profile/add',
        component: EditProfile,
        name: 'Add Profile',
        props: true
    },
    // {
    //     path: '/payment',
    //     component: PaymentViewVue,
    //     name: 'Payment',
    //     meta: {
    //         breadcrumb: false,
    //         app_bar: false,
    //         navigation_drawer: false
    //     }
    // },
]

