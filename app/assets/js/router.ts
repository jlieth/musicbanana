import routes from "@/routes.json"
import router from "@public/bundles/fosjsrouting/js/router.min.js"

router.setRoutingData(routes)
router.encode = (s: string) => { return encodeURIComponent(s) }

router.artistUrl = function(name: string): string {
    name = this.encode(name)
    return this.generate("music_overview", {artistName: name})
}

router.albumUrl = function(artist: string, title: string): string {
    artist = this.encode(artist)
    title = this.encode(title)
    return this.generate("music_album", {artistName: artist, albumTitle: title})
}

export default router
