import { mergeAttributes, getNodeAttributes } from '@tiptap/core'
import Image from '@tiptap/extension-image'

export default Image.extend({
  addAttributes() {
    return {
      ...this.parent?.(),
      class: {
        parseHTML: element => element.getAttribute('class')
      }
    }
  },
  renderHTML({ HTMLAttributes }) {
    return ['img', mergeAttributes(this.options.HTMLAttributes, HTMLAttributes)]
  },
  addCommands() {
    return {
      ...this.parent(),
      toggleClass: (params) => ({commands, editor}) => {
        let previousClass = getNodeAttributes(editor.state, 'image')?.class?.split(' ')?.filter(i => i) || []
        let currentClass = params?.class?.split(' ').filter(i => i) || []

        if(params.remove){
          previousClass = previousClass.filter(i => !i.match(params.remove))
        }

        let _class = ([
          ...currentClass.filter(i => !previousClass.includes(i))  || [],
          ...previousClass.filter(i => !currentClass.includes(i)) || [],
        ]).join(' ')

        return commands.updateAttributes('image', {class: _class})
      }
    }
  }
})
