import routes from "@/routes.json"
import router from "@public/bundles/fosjsrouting/js/router.min.js"

router.setRoutingData(routes)

router.artistUrl = function(name: string): string {
    return this.generate("music_overview", {artistName: name})
}

router.albumUrl = function(artist: string, title: string): string {
    return this.generate("music_album", {artistName: artist, albumTitle: title})
}

router.trackUrl = function(artist: string, title: string): string {
    return this.generate("music_track", {artistName: artist, trackTitle: title})
}

export default router
