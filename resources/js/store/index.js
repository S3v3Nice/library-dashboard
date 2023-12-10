import {createStore} from "vuex";
import auth from "./auth.js";

const store = createStore({
    modules: {
        auth
    }
})

export default store