import mitt from 'mitt'

export const FILE_UPLOAD_STARTED = 'FILE_UPLOAD_STARTED'
export const SHOW_ERROR_DIALOGS = 'SHOW_ERROR_DIALOGS'

export const emitter = mitt()

export function showErrorDialog(message){
    emitter.emit(SHOW_ERROR_DIALOGS, {message})
}