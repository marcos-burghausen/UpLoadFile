import { useAuthStore } from "@/store/auth";

export default async function routes(to, from, next) {
    //se existir o meta para a rota que estou indo
    if (to.meta?.auth) {
        const auth = useAuthStore();
        if (auth.isAuthenticated) {
            next();
        } else {
            next({ name: "home" });
        }
        // console.log(to.name);
    } else {
        next();
    }
}