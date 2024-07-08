export function isImage(file) {
    return ['jpg', 'jpeg', 'png', 'gif', 'bmp'].includes(file.mime)
}

export function isPDF(file) {
    return ['pdf'].includes(file.mime)
}

export function isAudio(file) {
    return ['mp3', 'ogg', 'wav', 'm4a', 'webm'].includes(file.mime)
}

export function isVideo(file) {
    return ['mp4', 'mpeg', 'ogg', 'mov', 'webm'].includes(file.mime)
}

export function isWord(file) {
    return ['doc', 'docx'].includes(file.mime)
}

export function isExcel(file) {
    return ['xls', 'xlsx'].includes(file.mime)
}

export function isPowerPoint(file) {
    return ['ppt', 'pptx'].includes(file.mime);
}

export function isZip(file) {
    return ['zip'].includes(file.mime)
}

export function isText(file) {
    return ['txt', 'csv'].includes(file.mime)
}

export function isHTML(file) {
    return ['html'].includes(file.mime);
}

export function isJavaScript(file) {
    return ['js'].includes(file.mime);
}

export function isCSS(file) {
    return ['css'].includes(file.mime);
}

export function isRAR(file) {
    return ['rar'].includes(file.mime);
}

export function isOtherType(file) {
    return !(
        isImage(file) ||
        isPDF(file) ||
        isAudio(file) ||
        isVideo(file) ||
        isWord(file) ||
        isExcel(file) ||
        isPowerPoint(file) ||
        isZip(file) ||
        isText(file) ||
        isHTML(file) ||
        isJavaScript(file) ||
        isCSS(file) ||
        isRAR(file)
    );
}