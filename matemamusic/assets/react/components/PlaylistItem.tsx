import React, { ReactNode } from 'react';
import { Playlist } from '../types/Playlist';
import { Grid, Paper, Typography } from '@mui/material';
import MusicNoteIcon from '@mui/icons-material/MusicNote';
import { DataGrid, GridColDef } from '@mui/x-data-grid';

type PlaylistItemProps = {
    playlist: Playlist;
}

const columns: GridColDef[] = [
    { field: 'id', headerName: 'ID', width: 70 },
    { field: 'name', headerName: 'Name', width: 70 },
    { field: 'author', headerName: 'Author', width: 70 },
    { field: 'link', headerName: 'Link', width: 70 },
];
  
export default function PlaylistItem(props: PlaylistItemProps): ReactNode {
    return (
        <Grid item xs={12} sm={6} key={`playlist-${props.playlist.id}`}>
        <Paper elevation={3}>
            <Typography component="h2" variant="h6"><MusicNoteIcon />{props.playlist.name}</Typography>
            <DataGrid 
                columns={columns}
                rows={props.playlist.trackList}
                initialState={{
                    pagination: {
                        paginationModel: { page: 0, pageSize: 5 },
                    },
                }}
                pageSizeOptions={[5, 10]}
            />
        </Paper>
        </Grid>
    )
}