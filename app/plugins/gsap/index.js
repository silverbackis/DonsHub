import { TimelineMax, TweenMax, Back, Elastic } from 'gsap'
import { ScrollToPlugin } from 'gsap/ScrollToPlugin'
import { CustomEase } from './CustomEase'
import { MorphSVGPlugin } from './MorphSVGPlugin'

// tree shaking prevent the plugin going bye bye in prod
// eslint-disable-next-line no-unused-vars
const plugins = [MorphSVGPlugin, Back, Elastic, ScrollToPlugin]

export default (ctx, inject) => {
  const injectObj = {
    timeline: TimelineMax,
    tween: TweenMax,
    customEase: CustomEase
  }
  ctx.gsap = injectObj
  inject('gsap', injectObj)
}
