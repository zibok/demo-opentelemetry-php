export type Playlist = {
    id: number;
    name: string;
    ownerId: number;
    trackList: {
        id: number,
    }[];
}
