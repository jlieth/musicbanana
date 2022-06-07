import routes from "@/routes.json"
import router from "@public/bundles/fosjsrouting/js/router.min.js"

router.setRoutingData(routes)
router.encode = (s: string) => { return encodeURIComponent(s) }

router.artistUrl = function(name: string): string {
    name = this.encode(name)
    return this.generate("music_overview", {artistName: name})
}

export default router
